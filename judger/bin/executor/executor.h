#ifndef _EXECUTER_H
#define _EXECUTER_H

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

#define FILENAME_LENGTH 128
#define LOGBUFF_SIZE 1024
//#define ENABLE_LOG

#ifdef ENABLE_LOG
	#define DPRINT(a,b) sprintf(logbuf,a,b),writelog(logbuf)
#else
	#define DPRINT(a,b)
#endif

enum
{
	RS_NML,  //Normal
	RS_RTE,  //Runtime Error
	RS_TLE,  //Time Limit Exceed
	RS_MLE,  //Memory Limit Exceed
	RS_OLE,  //Output Limit Exceed
	RS_SYS,  //Syscall Restricted
	RS_ECR,  //Executer Error
};

extern char program[FILENAME_LENGTH];
extern char logbuf[LOGBUFF_SIZE];
extern int time_limit, memory_limit, output_limit;

int read_config(char *config_file);
void permited_file_add(const char *value);
int permited_file_check(const char *value);

void forbidden_syscall_add(int syscall);
int forbidden_syscall_check(int syscall);

void execute();

void writelog(const char *message);

#endif /* _EXECUTER_H */
