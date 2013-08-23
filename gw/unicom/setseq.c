#include <stdio.h>
#include <sys/mman.h>
#include <fcntl.h>
#include <stdlib.h>
#include <sys/types.h>
#include <unistd.h>
static char *map;
static char* init_mmap()
{
  int fd;
  char *map=NULL;
                                                                                                                                                               
  fd=open("sendoff.mmap",O_RDWR,0600);
  if(fd<0)
    return 0;
    map=mmap(0,4,PROT_READ|PROT_WRITE,MAP_SHARED,fd,0);
    if (map==(char*)-1)
      map=0;
    close(fd);
    return map;
}
static read_mmap()
{
	printf("%d\n",*(unsigned int*)map);
}
static set_mmap(char *s)
{
	unsigned int seq;
	seq=atoi(s);
	if(seq<0)
	{
		printf("seq error!\n");
		exit(0);
	}
	*(unsigned int*)map=seq;
	printf("%d\n",*(unsigned int*)map);
}
main(int argc,char **argv)
{
	if( (argc!=1)&&(argc!=2) )
	{
		printf("arguments error!\n");
		exit(0);
	}
	map=init_mmap();
	if(argc==1)
		read_mmap();
	if(argc==2)
		set_mmap(argv[1]);
		
	
}
