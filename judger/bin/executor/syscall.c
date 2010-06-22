#include "executor.h"
#define MAX_SYSCALL 400

int forbidden_syscall[MAX_SYSCALL];

void forbidden_syscall_add(int syscall)
{
	if (syscall >= MAX_SYSCALL)
		return;
	forbidden_syscall[syscall] = 1;
}

int forbidden_syscall_check(int syscall)
{
	if (syscall >= MAX_SYSCALL)
		return 1;
	return forbidden_syscall[syscall];
}