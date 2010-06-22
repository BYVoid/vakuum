#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <math.h>
#include <sys/user.h>
#include <sys/types.h>
#include <sys/time.h>
#include <sys/resource.h>
#include <sys/wait.h>
#include <sys/ptrace.h>
#include <sys/syscall.h>
#include <libxml/xmlmemory.h>
#include <libxml/parser.h>

#include <dirent.h>

#include <iostream>
#include <vector>
#include <string>
#include <map>

using std::cerr;
using std::cout;
using std::endl;
using std::string;
using std::map;
using std::vector;

enum
{
	RS_NML,  //Normal
	RS_CE,   //Compile Error
};

enum
{
	CE_LANG,   //Language not exist
	CE_SIGNAL, //Compiler received signal
	CE_NOSRC,  //Source file not exist
	CE_NOBIN,  //Binary file not exist
};

char compile_command[1024];
char config_path[1024];
int time_limit,output_limit;
int cpid,time_used;

string compile_msg,src,bin,lang;
map<string,string> languages;

int stringToInt(const char *str)
{
	int num;
	sscanf(str,"%d",&num);
	return num;
}

void result(int signal,int option = 0)
{
	if (signal == RS_NML)
	{
		cerr << "Compiled successfully.";
	}
	else
	{
		if (option == CE_LANG)
			cerr << "No such language.";
		else if (option == CE_SIGNAL)
			cerr << "Compiler error.";
		else if (option == CE_NOSRC)
			cerr << "Source file not exist.";
		else if (option == CE_NOBIN)
			cerr << "Binary file not exist.";
	}
	cerr << endl;
	cout << signal << endl
		 << option << endl;
	exit(0);
}

void compile_user_source()
{
	struct rlimit rl_time_limit,rl_output_limit;
		
	//Set CPU Time Limit
	rl_time_limit.rlim_cur = time_limit;
	setrlimit(RLIMIT_CPU,&rl_time_limit);
	
	//Set Output File Size Limit
	rl_output_limit.rlim_max = rl_output_limit.rlim_cur = output_limit*1024;
	setrlimit(RLIMIT_FSIZE,&rl_output_limit);
	
	freopen("compiler_stdin.null","r",stdin);
	freopen("compiler_stdout.null","w",stdout);
	freopen("compiler_stderr.null","w",stderr);
	
	//Do compile
	execl("/bin/sh","sh","-c",compile_command,NULL);
}

int filter(const struct dirent *dir)
{
	int i;
	int n = strlen(dir->d_name);
	for(i=0;i<n;i++)
	{
		if (!isdigit(dir->d_name[i]))
			return 0;
	}
	return 1;
}

vector<int> getChildPid(int pid)
{
	struct dirent **namelist;
	char pid_path[128];
	vector<int> result;
	
	int total=scandir("/proc",&namelist,filter,alphasort);
    for(int i=0;i<total;i++)
    {
    	int ppid,tmp;
    	char tmp2[128];
		strcpy(pid_path,"/proc/");
		strcat(pid_path,namelist[i]->d_name);
		strcat(pid_path,"/stat");
		
		FILE *fp=fopen(pid_path,"r");
		fscanf(fp,"%d %s %s %d",&tmp,tmp2,tmp2,&ppid);
		
		fclose(fp);
		if (ppid == pid)
		{
			sscanf(namelist[i]->d_name,"%d",&tmp);
			result.push_back(tmp);
		}
	}
	
	return result;
}

void killProcessTree(int pid)
{
	vector<int> children = getChildPid(pid);
	for(vector<int>::iterator i = children.begin();i!=children.end();i++)
	{
		int cpid = *i;
		killProcessTree(cpid);
	}
	kill(pid,SIGKILL);
}

void timer(int signo)
{
	time_used++;
	if (time_used > time_limit)
	{
		killProcessTree(cpid);
	}
	alarm(1);
}

void setTimer()
{
	if (signal(SIGALRM,timer) == SIG_ERR)
	{
		perror("cannot set timer ");
		exit(0);
	}
	alarm(1);
	time_used = 0;
}

void compile()
{
	setTimer();

	if ((cpid=fork())==0)
	{
		//Child process to compile user source
		compile_user_source();
		exit(0);
	}
	else
	{
		int cstat;
		waitpid(cpid,&cstat,NULL);
		if ((WIFSIGNALED(cstat) && WTERMSIG(cstat)!=0)
		   || (WIFSTOPPED(cstat) && WSTOPSIG(cstat)!=0))
		{
			result(RS_CE,CE_SIGNAL);
		}
			
		if (access(bin.c_str(),0) == -1)
			result(RS_CE,CE_NOBIN);
		result(RS_NML);
	}
}

void set_option(const char *key,const char *value)
{
	if (strcmp(key,"time_limit") == 0)
	{
		time_limit = stringToInt(value);
		time_limit = (time_limit/1000) + (time_limit%1000?1:0);
	}
	else if (strcmp(key,"output_limit") == 0)
	{
		output_limit = stringToInt(value);
	}
	else if (strcmp(key,"messagefile") == 0)
	{
		compile_msg = value;
	}
	else
	{
		languages[key] = value;
	}
}

int read_config(char *config_file)
{
	xmlDocPtr doc;
	xmlNodePtr cur;
	xmlChar *value;

	doc = xmlParseFile(config_file);
	if (doc == NULL)
	{
		return 1;
	}
	
	cur = xmlDocGetRootElement(doc);
	
	if (xmlStrcmp(cur->name,(const xmlChar *)"document") != 0)
	{
		xmlFreeDoc(doc);
		return 1;
	}
	
	for (cur = cur->xmlChildrenNode;cur != NULL;cur = cur->next)
	{
		if (cur->type == 1)
		{
			value = xmlNodeListGetString(doc,cur->xmlChildrenNode, 1);
			set_option((const char *)cur->name,(const char *)value);
			xmlFree(value);
		}
	}
	
	xmlFreeDoc(doc);
	return 0;
}

void getConfigFile()
{
	sprintf(config_path,"/proc/%d/exe",getpid());
	config_path[readlink(config_path,config_path,1023)]='\0';
	strrchr(config_path,'/')[1]=0;
	sprintf(&config_path[strlen(config_path)],"compiler.xml");
}

void string_replace(string & strBig, const string & strsrc, const string &strdst)
{
	string::size_type pos=0;
	string::size_type srclen=strsrc.size();
	string::size_type dstlen=strdst.size();
	while( (pos=strBig.find(strsrc, pos)) != string::npos)
	{
		strBig.replace(pos, srclen, strdst);
		pos += dstlen;
	}
}

void init()
{
	getConfigFile();
	read_config(config_path);
	//Generate compile command
	if (languages.find(lang) != languages.end())
	{
		string command = languages[lang];
		string_replace(command,"{$source}",src);
		string_replace(command,"{$binary}",bin);
		string_replace(command,"{$message}",compile_msg);
		cerr << "Command: " << command << endl;
		strcpy(compile_command,command.c_str());
	}
	else
		result(RS_CE,CE_LANG);
	
	if (access(src.c_str(),0) == -1)
		result(RS_CE,CE_NOSRC);
	if (access(bin.c_str(),0) == 0)
		remove(bin.c_str());

}

int main(int argc,char **argv)
{
	lang = argv[1];
	src = argv[2];
	bin = argv[3];
	init();
	compile();
	return 0;
}
