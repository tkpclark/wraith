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
	sprintf(buf,"[%s][%s][%s]:%s\n",ts_nano,mdname,version,tmp);
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
