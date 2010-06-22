#include "executor.h"

int time_used;
int memory_used;
int cpid;

void setLimits()
{
	struct rlimit rl_time_limit,rl_output_limit;
	
	if (output_limit > 0)
	{
		//Set Output File Size Limit
		rl_output_limit.rlim_max = rl_output_limit.rlim_cur = output_limit*1024;
		setrlimit(RLIMIT_FSIZE,&rl_output_limit);
	}
}

void runProgram()
{
	//Redirect the standard input / output stream
	freopen("stdin","r",stdin);
	freopen("stdout","w",stdout);
	
	//Set ptrace
	ptrace(PTRACE_TRACEME,0,NULL,NULL);
	
	//Execute user program as this process
	execl(program,program,NULL);
}

void result(int rs,int option)
{
	ptrace(PTRACE_KILL,cpid,NULL,NULL);
	printf("%d %d %d %d\n",rs,option,time_used,memory_used);
	exit(0);
}

void peekstring(int addr,char *str)
{
	char *pstr = str;
	union peeker
	{
		long val;
		char chars[sizeof(long)];
	} data;
	int i,j,k=1;
	
	for(i=0;k;i++)
	{
		data.val = ptrace(PTRACE_PEEKDATA,cpid,addr+i*4,NULL);
		memcpy(pstr,data.chars,sizeof(long));
		for (j=0;j<sizeof(long);j++)
		{
			if (pstr[j] == 0)
			{
				k = 0;
				break;
			}
		}
		pstr += sizeof(long);
	}
}

int getMemoryUsed()
{
	FILE *fps;
	char ps[32];
	int memory;
	
	sprintf(ps,"/proc/%d/statm",cpid);
	fps = fopen(ps,"r");
	int i;
	for (i=0;i<6;i++)
	fscanf(fps,"%d",&memory);
	fclose(fps);
	
	int pagesize = getpagesize() / 1024;
	memory *= pagesize;
	return memory;
}

void witnessSyscall()
{
	struct user_regs_struct reg;
	int syscall;
	static int executed = 0;
	int forbidden = 0;
	
	ptrace(PTRACE_GETREGS,cpid,NULL,&reg);
	#ifdef __i386__
	syscall = reg.orig_eax;
	#else
	syscall = reg.orig_rax;
	#endif
	
	DPRINT("syscall: %d",syscall);
	
	if (forbidden_syscall_check(syscall))
		forbidden = 1;
	
	//permit the process to execve once
	if (syscall == SYS_execve)
	{
		if (!executed)
			executed = 1;
		else
			forbidden = 1;
	}
	
	//witness the file opened by process
	if (syscall == SYS_open)
	{
		char *filename = (char *)malloc(1024);
		peekstring(reg.ebx,filename);
		int flag = reg.ecx;
		DPRINT(" open file: [%s] ",filename);
		DPRINT("with flag: %d",flag);
		if (!permited_file_check(filename))
		{
			DPRINT("%s"," forbidden file opening\n");
			forbidden = 1;
		}
		free(filename);
	}
	
	//witness memory alloc
	if (syscall == SYS_execve
	 || syscall == SYS_brk
	 || syscall == SYS_mmap
	 || syscall == SYS_mmap2
	 || syscall == SYS_munmap )
	{
		int memory_current = getMemoryUsed();
		if (memory_current > memory_used)
			memory_used = memory_current;
		if (memory_limit > 0 && memory_used > memory_limit)
		{
			//Memory Limit Exceeds
			DPRINT("%s","MLE\n");
			result(RS_MLE,0);
		}
	}
	
	//Restricted syscall
	if (forbidden)
	{
		DPRINT("Forbidden syscall: %d\n",syscall);
		result(RS_SYS,syscall);
	}
	
	DPRINT("%s","\n");
}

void timer(int signo)
{
	kill(cpid,SIGUSR1);
	alarm(1);
}

void setTimer()
{
	if (time_limit > 0)
	{
		if (signal(SIGALRM,timer) == SIG_ERR)
		{
			perror("cannot set timer ");
			exit(0);
		}
		alarm(1);
	}
}

void execute()
{
	if ((cpid=fork())==0)
	{
		//Child process
		setLimits();
		runProgram();
		exit(0);
	}

	//Set Timer Start
	setTimer();
	
	struct rusage rinfo;
	int runstat;
	int suspend = 0;
	
	for(;;)
	{
		//Wait for a signal sent to child process
		wait4(cpid,&runstat,0,&rinfo);
		
		time_used = rinfo.ru_utime.tv_sec * 1000 + rinfo.ru_utime.tv_usec / 1000;
		DPRINT("time: %d ",time_used);
		DPRINT("[%d] ",++suspend);
		
		if (WIFEXITED(runstat))
		{
			//Program exited
			int exitcode = WEXITSTATUS(runstat);
			DPRINT("exited normally: %d ",exitcode);
			if (exitcode != 0)
			{
				//Runtime Error
				result(RS_RTE,-exitcode);
			}
			result(RS_NML,0);
		}
		else if (WIFSIGNALED(runstat))
		{
			//Killed by SIGKILL
			/*
			int signal = WTERMSIG(runstat);
			signal must be 9(SIGKILL)
			*/
			result(RS_ECR,SIGKILL);
		}
		else if (WIFSTOPPED(runstat))
		{
			int signal = WSTOPSIG(runstat);
			DPRINT("signal: %d ",signal);
			if (signal == SIGTRAP)
				witnessSyscall();
			else if (signal == SIGUSR1)
			{
				//Ignore SIGUSR1 which is a probe to check usertime
			}
			else if (signal == SIGXFSZ)
			{
				//Output limit exceeds
				DPRINT("%s","OLE\n");
				result(RS_OLE,0);
			}
			else
			{
				//Runtime error : Received a signal
				DPRINT("Runtime error : Received a signal %d\n",signal);
				result(RS_RTE,signal);
			}
		}
		else
			;//Should not be here
		
		if (time_limit > 0 && time_used > time_limit)
		{
			//Time limit exceeds
			DPRINT("%s","TLE\n");
			result(RS_TLE,0);
		}
		
		//Schedule next stop at syscall
		ptrace(PTRACE_SYSCALL,cpid,NULL,NULL);
	}
}