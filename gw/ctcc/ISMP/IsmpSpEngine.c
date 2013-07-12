#define _FILE_OFFSET_BITS 64
#include "soapH.h"
#include "IsmpSpEngineSoapBinding.nsmap"
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <ccl/ccl.h>

char logbuf[1024];
char mdname[32];
static char heapfile[128];
static char logfile[128];
int logfd;

static void read_config()
{
	struct ccl_t config;
	const struct ccl_pair_t *iter;
	config.comment_char = '#';
	config.sep_char = '=';
	config.str_char = '"';
	ccl_parse(&config, "../conf/IsmpSpEngine.ccl");
	while((iter = ccl_iterate(&config)) != 0)
	{
		if(!strcmp(iter->key,"logfile"))
			strcpy(logfile,iter->value);
		else if(!strcmp(iter->key,"mdname"))
			strcpy(mdname,iter->value);
		else if(!strcmp(iter->key,"heapfile"))
			strcpy(heapfile,iter->value);
	}
	ccl_release(&config);
}
static void my_init()
{
	read_config();
	
	logfd=open(logfile,O_WRONLY|O_APPEND);
	if(logfd==-1)
	{
		printf("open logfile error!\n");
		exit(0);
	}
	
	//proclog(logfd,"starting...");
}
int main(int argc, char **argv)
{ 
	SOAP_SOCKET m, s; /* master and slave sockets */
	my_init();
	struct soap soap;
	soap_init(&soap);
	/*
	soap_set_recv_logfile(&soap, debuglog);
	soap_set_send_logfile(&soap, debuglog);
	soap_set_test_logfile(&soap, debuglog);
	*/
	soap_set_mode(&soap, SOAP_C_UTFSTRING);
	soap_serve(&soap);
	return 0;
}


int __ns1__orderRelationUpdateNotify(struct soap *soap, struct ns2__OrderRelationUpdateNotifyReq *ns2__OrderRelationUpdateNotifyReq,struct ns3__Response *ns3__Response)
{
	
	ns3__Response->streamingNo=ns2__OrderRelationUpdateNotifyReq->streamingNo;
    ns3__Response->resultCode=0;
	
	sprintf(logbuf,"[orderRelationUpdateNotify] sNo[%s]userID[%s]prodID[%s]packID[%s]OPType[%d]",
		ns2__OrderRelationUpdateNotifyReq->streamingNo,
		ns2__OrderRelationUpdateNotifyReq->userID,
		ns2__OrderRelationUpdateNotifyReq->productID,
		ns2__OrderRelationUpdateNotifyReq->packageID,
		ns2__OrderRelationUpdateNotifyReq->OPType);
	proclog(logfd,logbuf);
	//writing heapfile
	int fd;
	fd=open(heapfile,O_WRONLY|O_APPEND);
	if(fd==-1)
	{
		proclog(logfd,"open heapfile error!\n");
		exit(0);
	}
	
	/*
	sNo 2
	userID 100
	prodID 120
	packID 150
	OPType 200
	*/
	char buf[256];
	strcpy(buf,"2");
	if(ns2__OrderRelationUpdateNotifyReq->streamingNo)
		strcpy(buf+2,ns2__OrderRelationUpdateNotifyReq->streamingNo);
	if(ns2__OrderRelationUpdateNotifyReq->userID)
		strcpy(buf+100,ns2__OrderRelationUpdateNotifyReq->userID);
	if(ns2__OrderRelationUpdateNotifyReq->productID)
		strcpy(buf+120,ns2__OrderRelationUpdateNotifyReq->productID);
	if(ns2__OrderRelationUpdateNotifyReq->packageID)
		strcpy(buf+150,ns2__OrderRelationUpdateNotifyReq->packageID);
	
	char tmp[4];
	sprintf(tmp,"%d",ns2__OrderRelationUpdateNotifyReq->OPType);
	strcpy(buf+200,tmp);
	
	write(fd,buf,sizeof(buf));
	close(fd);
	
	
  return SOAP_OK;
}


int __ns1__serviceConsumeNotify(struct soap *soap, struct ns2__ServiceConsumeNotifyReq *ns2__ServiceConsumeNotifyReq, struct ns3__Response *ns3__Response)
{
	char gbcontent[512];
	memset(gbcontent,0,sizeof(gbcontent));
	to_gb(ns2__ServiceConsumeNotifyReq->featureStr,gbcontent);
	sprintf(logbuf,"[serviceConsumeNotify]sNo[%s]featureStr[%s]linkID[%s]userID[%s]productID[%s]",ns2__ServiceConsumeNotifyReq->streamingNo,gbcontent,ns2__ServiceConsumeNotifyReq->linkID,ns2__ServiceConsumeNotifyReq->userID,ns2__ServiceConsumeNotifyReq->productID);
	proclog(logfd,logbuf);

    ns3__Response->streamingNo=ns2__ServiceConsumeNotifyReq->streamingNo;
    ns3__Response->resultCode=0;
    return SOAP_OK;
}


int __ns1__notifyManagementInfo(struct soap *soap, struct ns2__NotifyManagementInfoReq *ns2__NotifyManagementInfoReq,struct ns3__NotifyManagementInfoRsp *ns3__NotifyManagementInfoRsp)
{
		sprintf(logbuf,"[notifyManagementInfo]sNo[%s]ID[%s]",ns2__NotifyManagementInfoReq->streamingNo,ns2__NotifyManagementInfoReq->ID);
		proclog(logfd,logbuf);
    ns3__NotifyManagementInfoRsp->streamingNo=ns2__NotifyManagementInfoReq->streamingNo;
    ns3__NotifyManagementInfoRsp->resultCode=0;
    return SOAP_OK;
}
