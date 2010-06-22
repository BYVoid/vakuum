/***************************************************************************
 *  Vakuum Standard Checker
 *  std_checker.cpp
 *
 *  Compare the standard answer with user answer ignoring '\n' and space 
 *  at the end of the file
 *  比较输出文件和答案文件，忽略文件末尾的空格和换行符
 *  
 *  Copyright  2009  BYVoid
 *  Email byvoid1@gmail.com
 ****************************************************************************/

#include <stdio.h>
#include <stdlib.h>

enum
{
	RLT_CORRECT,
	RLT_NOFILE,
	RLT_MISMATCH,
	RLT_SHORTER,
	RLT_LONGER,
};

char *file_input,*file_output,*file_answer;
FILE *fp_in,*fp_out,*fp_ans;
int position;

void Answer(double score,int result)
{
	printf("%.2lf\n",score);
	switch (result)
	{
		case RLT_CORRECT:
			printf("Correct.");
			break;
		case RLT_MISMATCH:
			printf("Mismatch at %d.",position);
			break;
		case RLT_SHORTER:
			printf("Your answer is shorter than the standard.");
			break;
		case RLT_LONGER:
			printf("Your answer is longer than the standard.");
			break;
		case RLT_NOFILE:
			printf("Output file not found.");
			break;
		default:
			printf("Unknown error.");
	}
	printf("\n");
	exit(0);
}

void compare()
{
	fp_in = fopen(file_input,"r");
	fp_out = fopen(file_output,"r");
	fp_ans = fopen(file_answer,"r");
	
	if (fp_out == NULL)
		Answer(0,RLT_NOFILE);
	
	int co,ca;
	for (position = 0;;++position)
	{
		co = getc(fp_out);
		ca = getc(fp_ans);
		//output ended but answer not
		if (feof(fp_out))
		{ 
			while (!feof(fp_ans))
			{
				if (ca != '\n' && ca !=' ')
					Answer(0.0,RLT_SHORTER);
				ca = getc(fp_ans);
			}
			return Answer(1.0,RLT_CORRECT);
		}
		//answer ended but output not
		if (feof(fp_ans))
		{ 
			while (!feof(fp_out))
			{
				if (co != '\n' && co !=' ')
					Answer(0.0,RLT_LONGER);
				co = getc(fp_out);
			}
			return Answer(1.0,RLT_CORRECT);
		}

		if (co != ca)
			return Answer(0.0,RLT_MISMATCH);
	}
}

int main(int argc,char **argv)
{
	file_input = argv[1];
	file_output = argv[2];
	file_answer = argv[3];
	compare();
	return 0;
}
