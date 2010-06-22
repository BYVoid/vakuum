#include "executor.h"

char program[FILENAME_LENGTH],config_path[FILENAME_LENGTH];
int time_limit;
int memory_limit;
int output_limit;

void getConfigFile()
{
	sprintf(config_path,"/proc/%d/exe",getpid());
	config_path[readlink(config_path,config_path,1023)]='\0';
	strrchr(config_path,'/')[1]=0;
	sprintf(&config_path[strlen(config_path)],"executor.xml");
}

int main(int argc,char *argv[])
{
	getConfigFile();
	read_config(config_path);
	int i;
	for (i=1;i<argc;i++)
		read_config(argv[i]);
	execute();
	return 0;
}
