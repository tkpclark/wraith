#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <sys/mman.h>
#include <signal.h>
#include <sys/shm.h>
#include <fcntl.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <errno.h>
#include <ccl/ccl.h>
#include <mysql.h>
#include "soapH.h"
#include "SmsNotificationServiceSoapBinding.nsmap"


char mdname[32]="sendsms";
char logpath[256];
char version[]="1.00";
static int quit;
static int prtpid;
static int rcdlen;
static char mmapfile[128];
static char logfile[128];
static MYSQL mysql;
static char serverurl[256];
static char cpID[32];
static char key[32];

static char dbip[16];
static char dbuser[16];
static char dbpass[32];
static char dbname[32];

static char *p_map;
static unsigned int mmapsize;

char *init_mmap(char *pathname,unsigned int msize);

static void acquit(int signo)
{
	quit=1;
	//proclog(logfd,"MESSAGE:got quiting signal");
}
static void gofulfil(int signo)
{
	;//proclog(logfd,"MESSAGE:go fetch data!\n");
}
static void procquit(void)
{
  	proclog("MESSAGE:quited!");
}
static void read_config()
{
	struct ccl_t config;
	const struct ccl_pair_t *iter;
	config.comment_char = '#';
	config.sep_char = '=';
	config.str_char = '"';
	ccl_parse(&config, "../conf/ctccgw.ccl");
	while((iter = ccl_iterate(&config)) != 0)
	{
		if(!strcmp(iter->key,"mmapfile"))
			strcpy(mmapfile,iter->value);
		else if(!strcmp(iter->key,"logpath"))
			strcpy(logpath,iter->value);
		else if(!strcmp(iter->key,"mmapsize"))
			mmapsize=atoi(iter->value);
		else if(!strcmp(iter->key,"rcdlen"))
			rcdlen=atoi(iter->value);
		else if(!strcmp(iter->key,"serverurl"))
			strcpy(serverurl,iter->value);
		else if(!strcmp(iter->key,"cpID"))
			strcpy(cpID,iter->value);
		else if(!strcmp(iter->key,"key"))
			strcpy(key,iter->value);

		else if(!strcmp(iter->key,"ip"))
			strcpy(dbip,iter->value);
		else if(!strcmp(iter->key,"user"))
			strcpy(dbuser,iter->value);
		else if(!strcmp(iter->key,"pass"))
			strcpy(dbpass,iter->value);
		else if(!strcmp(iter->key,"db"))
			strcpy(dbname,iter->value);
	}
	ccl_release(&config);
}


char* getTimeStamp(char *ts)
{
	time_t tt;
	tt=time(0);
	strftime(ts,16,"%m%d%H%M%S",localtime(&tt));
	return ts;
}

char* getspPwd(char* pwd,char* ts)
{
	int i;
	//char *key="351001355";
	char buf[64]={0};
	char tmp[64]={0};
	strcpy(buf,cpID);
	strcpy(buf+strlen(cpID),key);
	strcpy(buf+strlen(cpID)+strlen(key),ts);
	MD5(buf,strlen(cpID)+strlen(key)+strlen(ts),tmp);
	for(i=0;i<16;i++)
		sprintf(pwd+2*i,"%02X",*((unsigned char*)(tmp+i)));
	//printf("%s\n",pwd);
	return pwd;
}

char *tostr(int seqid,char *strseqid)
{
	sprintf(strseqid,"%d",seqid);
	return strseqid;
}


static fulfil(char *p_data)
{
			char cpPassword[128];
			char timeStamp[16];
	
			struct soap soap;
			struct ns1__SmsMessage ns1__SmsMessage;
			struct ns4__RequestSOAPHeader ns4__RequestSOAPHeader;

			ns4__RequestSOAPHeader.cpID=cpID;
			getTimeStamp(timeStamp);
			memset(cpPassword,0,sizeof(cpPassword));
			getspPwd(cpPassword,timeStamp);
			ns4__RequestSOAPHeader.cpPassword=cpPassword;
			ns4__RequestSOAPHeader.timeStamp=timeStamp;
	
			ns4__RequestSOAPHeader.productID=p_data;
			ns4__RequestSOAPHeader.linkID=p_data+320;


			ns1__SmsMessage.receiverAddress=p_data+40;
			ns1__SmsMessage.senderAddress=p_data+260;
			ns1__SmsMessage.message=p_data+60;



			proclog("cpID[%s]pwd[%s]ts[%s]src[%s]dest[%s]pid[%s]linkID[%s]msg[%s]",
					ns4__RequestSOAPHeader.cpID,
					ns4__RequestSOAPHeader.cpPassword,
					ns4__RequestSOAPHeader.timeStamp,
					ns1__SmsMessage.senderAddress,
					ns1__SmsMessage.receiverAddress,
					ns4__RequestSOAPHeader.productID,
					ns4__RequestSOAPHeader.linkID,
					ns1__SmsMessage.message
					);

			SOAP_SOCKET m, s; /* master and slave sockets */
			soap_init(&soap);
			soap.header = (struct SOAP_ENV__Header *)soap_malloc(&soap, sizeof(struct SOAP_ENV__Header));
			soap_set_mode(&soap, SOAP_C_UTFSTRING);
			soap.header->ns4__RequestSOAPHeader=&ns4__RequestSOAPHeader;

			//proclog("server:[%s]", serverurl);
			//soap_call___ns1__sendSms(&soap, serverurl, NULL,&ns2__sendSms,&ns2__sendSmsResponse);
			soap_call_ns1__notifySmsReception(&soap, serverurl, NULL, &ns1__SmsMessage, NULL);
			if (soap.error)
			{
				//printf("SendSms:\n");
			   	soap_print_fault(&soap, stderr);
			   	proclog("soap error!");
			}

			soap_destroy(&soap);
			soap_end(&soap);
			soap_done(&soap);






			///
}
main()
{
	int i;
	int lockfd;
	unsigned int curnum=20000;
	unsigned int leftnum=0;
	void *p_chd;
	char tmp[8]={0};
	struct sigaction signew;
	
	sprintf(mdname, "%s-%d", mdname, getpid());

	prtpid=getppid();
	if(atexit(&procquit))
	{
	   printf("gstop code can't install!");
	  kill(prtpid,SIGINT);
		pause();
	}
	read_config();
	//sprintf(tmp,"|%d",getpid());
	//strcat(mdname,tmp);


	lockfd=open("tkp.lck",O_CREAT|O_WRONLY|O_TRUNC,S_IRWXU);
	if(lockfd==-1)
	{
		printf("open lock file error!\n");
		kill(prtpid,SIGINT);
		pause();
	}
	
	signew.sa_handler=SIG_IGN;
	sigaction(SIGPIPE,&signew,0);

	signew.sa_handler=acquit;
	sigemptyset(&signew.sa_mask);
	signew.sa_flags=0;
  	sigaction(SIGINT,&signew,0);

	signew.sa_handler=acquit;
	sigemptyset(&signew.sa_mask);
	signew.sa_flags=0;
	sigaction(SIGTERM,&signew,0);

	signew.sa_handler=gofulfil;
	sigemptyset(&signew.sa_mask);
	signew.sa_flags=0;
	sigaction(SIGUSR1,&signew,0);
	
	mysql_init(&mysql);
	   if(!mysql_real_connect(&mysql,dbip,dbuser,dbpass,dbname,0,NULL,0))
	   {
		sql_err_log(&mysql);
		exit(0);
	   }
	
	
	p_map=init_mmap(mmapfile,mmapsize);
	if(!p_map)
	{
		kill(prtpid,SIGINT);
		pause();
	}
	p_chd=malloc(rcdlen);
	while(1)
	{
		if(quit)
			exit(0);
		alarm(200);
		myflock(lockfd,1);
		if(!*(unsigned int*)(p_map+2*sizeof(unsigned int)))
		{
			myflock(lockfd,2);
			//proclog(MESSAGE:logfd,"no data now");
			//pause();
			//mysql_exec(&mysql,"set names gbk");
			mysql_ping(&mysql);
			sleep(60);
			continue;
		}
		curnum=*(unsigned int*)(p_map+sizeof(unsigned int));
		leftnum=*(unsigned int*)(p_map+2*sizeof(unsigned int));
		memcpy(p_chd,p_map+512+curnum*rcdlen,rcdlen);

		*(unsigned int*)(p_map)+=1;
		(*(unsigned int*)(p_map+sizeof(unsigned int)))+=1;
		(*(unsigned int*)(p_map+2*sizeof(unsigned int)))-=1;

		myflock(lockfd,2);
		if(leftnum==1)
		{
			kill(prtpid,SIGUSR1);
		}
		fulfil(p_chd);
		alarm(0);
	}
}
