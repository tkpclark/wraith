

#include "lib.h"
#include "sk.h"
#include "sgip.h"

static const char *pidfile="send.pid";
static int sd;
static unsigned int *psoff;
static unsigned int seq=0;
static char nodeId[16];//="3024051405"
static char corpId[16];//="51405"
static char gateip[32];//="218.60.136.119"
static char port[16];//="8801"
static char username[16];//="syqp51405"
static char password[16];//="syqp51405"
static unsigned int idle_point;//time of no date
static int bind_flag;
static char gwid[4];
skt_s *sp;


char mdname[]="unimo";
char logpath[] = "../logs/unicomgw/";
char version[]="1.00";

static char dbip[32];
static char db[32];
static char dbuser[32];
static char dbpass[32];
static char gwid[4];

static MYSQL mysql;

static void acquit(int signo)
{
	proclog("got quit signal\n");
	exit(0);
}
static void procquit(void)
{
  	proclog("quiting!\n");
  	unlink(pidfile);
}
init()
{
	struct sigaction signew;

	
	signew.sa_handler=acquit;
	sigemptyset(&signew.sa_mask);
	signew.sa_flags=0;
  	sigaction(SIGINT,&signew,0);
	
	signew.sa_handler=acquit;
	sigemptyset(&signew.sa_mask);
	signew.sa_flags=0;
	sigaction(SIGTERM,&signew,0);
	
	signew.sa_handler=acquit;
	sigemptyset(&signew.sa_mask);
	signew.sa_flags=0;
	sigaction(SIGQUIT,&signew,0);
	
	write_pid(pidfile);
	
	if(atexit(&procquit))
	{
	   printf("gstop code can't install!");
	   exit(0);
	}
	
	//read_config();

/*
	mysql_init(&mysql);
	if (!mysql_real_connect(&mysql,dbip,dbusr,dbpas,dbname,0,NULL,0))
	{
		sql_err_log(&mysql);
		exit(0);
	}
*/	
	
	psoff=(unsigned int*)init_mmap("sendoff.mmap",4);
	//printf("start from %d\n",*psoff);

	
}
static int sgip_bind(char *gateip,int port,unsigned int nodeId,char *username,char *password,unsigned int seq)
{
	unsigned char pak[128];
	unsigned char nownow[256];
	unsigned char response[64];
	unsigned char *pp;
	int i;

	memset(pak,0,sizeof(pak));
	memset(response,0,sizeof(response));
	pp=pak;
	*(long *)pp=htonl(61);
	*(long *)(pp+4)=htonl(0x00000001);
	*(long *)(pp+8)=htonl(nodeId);
	nownow[0]=0;
	tstring(nownow);
	*(long *)(pp+12)=htonl(atoll(nownow));
	*(long *)(pp+16)=htonl(seq);
	*(pp+20)=(unsigned char)1;
	strcpy(pp+21,username);
	//strcpy(pp+37,gshare_key);
	strcpy(pp+37,password);
	//proclog( "login... ip:[%s:%d] name[%s] passwd[%s]\n",gateip,port,username,password);
	
	sp=(skt_s*)sopen();
	if(sclient(sp,gateip,port)==-1) 
	{
		proclog("failed to connect to %s:%d,%s\n",gateip,port,strerror(errno));
		exit(0);
	}
	//if((cmppsd=sclient(sp,ggate_ip,5088))==-1) exit(0);
	
	if(write(sp->sd,pak,61)==-1)
	{
		proclog("failed to send login cmd!%s\n",strerror(errno));
		exit(0);
	}
	if((i=recv(sp->sd,response,29,MSG_WAITALL))==-1) 
	{
		proclog( "failed to recv login response!");
		exit(0);
	}
	//cmd=ntohl(*((unsigned long *)(response+4)));
	//if(cmd!=0x80000001) exit(0);
	if(response[20]==0)
	{
		//proclog( "bind status is:%d\n",response[20]);
		bind_flag=1;
	}
	else
	{
		proclog( "bind status is:%d\n",response[20]);
		exit(0);
	}
	
	return (int)response[20]; 
}


static int sgip_unbind(char *nodeid,unsigned int seq)
{
	unsigned char pak[70];
	unsigned char nownow[64];
	unsigned char response[40];
	unsigned char *pp;
	int n;

	memset(pak,0,70);
	memset(response,0,40);
	pp=pak;
	//proclog("sending unbind cmd...\n");
	*(long *)pp=htonl(20);
	*(long *)(pp+4)=htonl(0x00000002);
	*(long *)(pp+8)=htonl(atoi(nodeid));
	//*(long *)(pp+12)=htonl(825140000);
	nownow[0]=0;
	tstring(nownow);
	*(long *)(pp+12)=htonl(atol(nownow));
	*(long *)(pp+16)=htonl(seq);//seq

	bind_flag=0;
	
	if(writeall(sp->sd,pak,20)==-1)
	{
		proclog("failed int unbind:%s\n",strerror(errno));
		return;
	}

	n=recv(sp->sd,response,20,MSG_WAITALL);
	
	//proclog("recved %d bytes\n",n);
	sclose(sp);

}

/*
unsigned long s4,
int mtclen,
char *content,
char *service_id,
char *src_id,
char feetype,
char MTFlag,
char RPFlag,
char *feecode,
char *des_id,
char *l88,
char *linkid
*/
static void sgip_submit(SUBMIT_PKG *p_submit_pkg,int nodeId, unsigned int seq)
{
	unsigned char buffer[1024];
	unsigned char nownow[300];
	unsigned char *pp;

	unsigned long pkg_len=0;
	int n=0;
	int cmd=0x00000003;

	memset(buffer,0,sizeof(buffer));
	pp=buffer;
	*(long *)(pp+4)=htonl(cmd);
	*(long *)(pp+8)=htonl(nodeId);
	nownow[0]=0;
	tstring(nownow);
	*(long *)(pp+12)=htonl(atol(nownow));
	*(long *)(pp+16)=htonl(seq);
	strcpy(pp+20,p_submit_pkg->SPNumber);//sp�������
	strcpy(pp+41,p_submit_pkg->ChargeNumber);//���Ѻ���
	*(pp+62)=(unsigned char)1;//Ŀ�ĺ�������
	strcpy(pp+63,p_submit_pkg->UserNumber);//Ŀ�ĺ���
	strcpy(pp+84,p_submit_pkg->CorpId);//��ҵ����
	strcpy(pp+89,p_submit_pkg->ServiceType);//�ƷѴ���(ҵ�����)
	*(pp+99)=p_submit_pkg->FeeType;//�Ʒ�����
	strcpy(pp+100,p_submit_pkg->FeeValue);//�Ʒѽ��
	strcpy(pp+106,"0");//�����û��Ļ��ѣ���λΪ��
	*(pp+112)=(unsigned char)0;//0:Ӧ�գ�1��ʵ��
	*(pp+113)=(unsigned char)3;//0;//0��mo�㲥����ĵ�һ��mt��1��mo�㲥����ķǵ�һ��mt��2��mo�㲥�����mt��3��ϵͳ���������mt��
	*(pp+114)=(unsigned char)8;//���ȼ�0-9�ӵ͵���
	*(pp+147)=(unsigned char)1;//10��ֻ�д���ŷ���rp��1��ʼ�շ���rp��2:������rp��3�����¿۷���Ϣ��Ҫ����rp
	*(pp+150)=(unsigned char)15;//�����ʽ0����ascii����3��д��������4���������룻8��ucs2���룻15��gbk����
	*(long *)(pp+152)=htonl(p_submit_pkg->MessageLength);
	pkg_len=p_submit_pkg->MessageLength+164;
	
	*(long *)pp=htonl(pkg_len);
	strcpy(pp+156,p_submit_pkg->MessageContent);
	strcpy(pp+156+p_submit_pkg->MessageLength,p_submit_pkg->linkid);
	
	proclog("seq[%u]|ChargeNumber[%s]CorpId[%s]FeeType[%d]FeeValue[%s]MessageContent[%s]MessageLength[%d]ServiceType[%s]SPNumber[%s]UserNumber[%s]linkid[%s]bind[%d]\n",
			seq,
			p_submit_pkg->ChargeNumber,
			p_submit_pkg->CorpId,
			p_submit_pkg->FeeType,
			p_submit_pkg->FeeValue,
			p_submit_pkg->MessageContent,
			p_submit_pkg->MessageLength,
			p_submit_pkg->ServiceType,
			p_submit_pkg->SPNumber,
			p_submit_pkg->UserNumber,
			p_submit_pkg->linkid,
			bind_flag);

	if(writeall(sp->sd,buffer,pkg_len)==-1)
	{
		proclog("submit failed! %s\n",strerror(errno));
		exit(0);
	}

	return;
}
static read_response()
{
	char buffer[256];
	memset(buffer,0,sizeof(buffer));
	int n;
	n=read(sp->sd,buffer,sizeof(buffer)-1);
	//proclog("resp:%d bytes,cmd:0x%X,result:%d\n",n,ntohl(*(int*)(buffer+4)),buffer[20]);
	proclog("result:%d\n",buffer[20]);
}
static int new_data()
{
	//if(get_file_size(mtgwfd) > *(int*)(psoff)*PKG_LENGTH )
		return 1;
	else
		return 0;
//	printf("sent:%d\n",*(int*)(psoff));
}
static send_all_data()
{
	char buffer[PKG_LENGTH];
	lseek(mtgwfd,(off_t)PKG_LENGTH*(*(int*)(psoff)),SEEK_SET);
	int n=0;
	SUBMIT_PKG submit_pkg;
	while(1)
	{
		memset(buffer,0,PKG_LENGTH);
		n=read(mtgwfd,buffer,PKG_LENGTH);
		if(!n)
			break;

		memcpy(&submit_pkg,buffer,sizeof(SUBMIT_PKG));
		strcpy(submit_pkg.CorpId,corpId);
		sgip_submit(&submit_pkg,atoll(nodeId),++seq);
		read_response();
		(*(int*)(psoff))++;
	}
}
/*
static send_mt()
{
	sgip_bind(gateip,atoi(port),atoll(nodeId),username,password,++seq);
	send_all_data();
	sgip_unbind(nodeId,++seq);
}
*/
static read_config()
{
	struct ccl_t config;
	const struct ccl_pair_t *iter;
	config.comment_char = '#';
	config.sep_char = '=';
	config.str_char = '"';
	ccl_parse(&config, "app.config");
	while((iter = ccl_iterate(&config)) != 0)
	{
		if(!strcmp(iter->key,"nodeId"))
			strcpy(nodeId,iter->value);
		else if(!strcmp(iter->key,"corpId"))
			strcpy(corpId,iter->value);
		else if(!strcmp(iter->key,"gateip"))
			strcpy(gateip,iter->value);
		else if(!strcmp(iter->key,"port"))
			strcpy(port,iter->value);
		else if(!strcmp(iter->key,"gwid"))
			strcpy(gwid,iter->value);
		else if(!strcmp(iter->key,"username"))
			strcpy(username,iter->value);
		else if(!strcmp(iter->key,"password"))
			strcpy(password,iter->value);
		else if(!strcmp(iter->key,"dbip"))
			strcpy(dbip,iter->value);
		else if(!strcmp(iter->key,"db"))
			strcpy(db,iter->value);
		else if(!strcmp(iter->key,"dbuser"))
			strcpy(dbuser,iter->value);
		else if(!strcmp(iter->key,"dbpass"))
			strcpy(dbpass,iter->value);

	}
	ccl_release(&config);
}
main()
{
	read_config();
	init();

	idle_point=time(0);
	
	while(1)
	{
		if(new_data())
		{
			//printf("new data!\n");
			if(!bind_flag)
			{
				sgip_bind(gateip,atoi(port),atoll(nodeId),username,password,++seq);
			}
			send_all_data();
			idle_point=time(0);
			continue;
		}
		//printf("%d\n",time(0)-idle_point);
		else
		{
			if(bind_flag)
			{
				if(time(0)-idle_point > 5)
				{
					sgip_unbind(nodeId,++seq);
				}
			}
		}
		
		sleep(1);
	}	
}

