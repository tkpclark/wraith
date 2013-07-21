#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <time.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <sys/mman.h>
#include <errno.h>
//#include <errmsg.h>
#include <stdarg.h>
#include <iconv.h>
#include <unistd.h>

extern char mdname[];
extern char logpath[];
extern char version[];


void proclog(const char *fmt,...)
{
	char ts[32];
	char buf[2048]; //logout
	char tmp[2048];//came in
	time_t tt;
	struct timeval tv;



	memset(tmp,0,sizeof(tmp));
	va_list vs;
	va_start(vs,fmt);
	vsprintf(tmp,fmt,vs);
	va_end(vs);

	//get log time
	tt=time(0);
	memset(buf,0,sizeof(buf));
	strftime(ts,30,"%F %X",(const struct tm *)localtime(&tt));

	char ts_nano[64];
	gettimeofday (&tv, NULL);
	sprintf(ts_nano,"%s.%06d",ts, tv.tv_usec);


	int fd;

	//log content
	sprintf(buf,"[%s][%s][%s]: %s\n",ts_nano,mdname,version,tmp);
	//printf("%s",buf);



	// get filename
	char filename[128];
	strftime(ts,30,"%Y%m%d",(const struct tm *)localtime(&tt));
	sprintf(filename,"%s/%s.log", logpath,ts);

	fd=open(filename, O_CREAT|O_WRONLY|O_APPEND,0644);
	if(fd <0)
	{
		printf("open %s failed!%s\n",filename,strerror(errno));
		return;
	}
	//T_DES(1,key,des_len,buf,buf);
	flock(fd,LOCK_EX);
	//write(fd,buf,sizeof(buf));
	write(fd,buf,strlen(buf));
	flock(fd,LOCK_UN);
	close(fd);

}
int to_utf(char *in,char *out)
{
          int i;
             char *putfout;
             char *pout;
             size_t ll1;
             size_t ll2;
             iconv_t cd;
             ll1=strlen(in);
             ll2=1000;
             putfout=out;
             pout=in;
             cd=iconv_open("utf-8","gb2312");
             if(cd==(iconv_t)-1)
                {
                        exit(0);
                }
             i=(size_t)iconv(cd,&pout,&ll1,&putfout,&ll2);
             *putfout=0;
             iconv(cd,NULL,NULL,NULL,NULL);
             iconv_close(cd);
             return i;
}
void to_gb(char *in,char *out)
{
        char *putfout;
        char *pout;
        size_t ll1;
        size_t ll2;
        iconv_t cd;
        ll1=1000;
        ll2=1000;
        putfout=out;
        pout=in;
        cd=iconv_open("gb2312","utf-8");
        if(cd==(iconv_t)-1)
        {
                printf("!!! can NOT cd!!");
                exit(0);
        }
        iconv(cd,&pout,&ll1,&putfout,&ll2);
        iconv_close(cd);
}
myflock(int lockfd,char type)
{
		if(type==1)
		{
	        if(flock(lockfd,LOCK_EX))
	        {
	        	proclog("lock error!\n");
	            exit(0);
	        }
	        //proclog(logfd,"lock");
	    }
	    else if(type==2)
	    {
	    	if(flock(lockfd,LOCK_UN))
	        {
	        	proclog("unlock error!\n");
	            exit(0);
	        }
	        //proclog(logfd,"unlock");
	    }
	    else;
}
char *init_mmap(char *pathname,unsigned int msize)
{
	int fd;
	struct stat statbuf;
	char *p_map=NULL;
	fd=open(pathname,O_RDWR|O_CREAT,0600);
	if(fd<0)
	{
		proclog("ERROR:open %s error!",pathname);
		return NULL;
	}
	if (fstat(fd, &statbuf) < 0)
	{
		proclog("ERROR:fstat %s error\n",pathname);
		return NULL;
	}
	if(statbuf.st_size!=msize)
	{
		void *p=NULL;
		p=malloc(msize);
		memset(p,0,msize);
		write(fd,p,msize);
	}
	if ((p_map = mmap(0, msize, PROT_READ|PROT_WRITE, MAP_SHARED,fd, 0)) == MAP_FAILED)
	{
		proclog("ERROR:%s mmap error!\n",pathname);
		return NULL;
	}
	if (p_map==(char*)-1)
		p_map=0;
	close(fd);
//	proclog("mmap size:%d",msize);
//	proclog(logfd,logbuf);
	//syslog(LOG_INFO,"%d",statbuf.st_size);
	return p_map;
}
char* init_mmap_read(char *pathname)
{
	int fd;
	char *map=NULL;
	struct stat statbuf;
	fd=open(pathname,0);
	if(fd<0)
	{
		proclog("ERROR:open %s error!",pathname);
		return NULL;
	}
	if (fstat(fd, &statbuf) < 0)
	{
		proclog("ERROR:fstat %s error!",pathname);
		return NULL;
	}
	if ((map = mmap(0, statbuf.st_size, PROT_READ, MAP_SHARED,fd, 0)) == MAP_FAILED)
	{
		proclog("ERROR:map %s error!",pathname);
		return NULL;
	}
	if (map==(char*)-1)
		map=0;
	close(fd);
	//syslog(LOG_INFO,"%d",statbuf.st_size);
	return map;
}
