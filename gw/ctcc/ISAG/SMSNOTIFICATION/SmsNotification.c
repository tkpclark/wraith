#define _FILE_OFFSET_BITS 64
#include "soapH.h"
#include "SmsNotificationBinding.nsmap"
#include <ccl/ccl.h>
//#include "apache_gsoap.h"
//IMPLEMENT_GSOAP_SERVER()

char logbuf[1024];
char mdname[32];
static char logfile[128];
static char heapfile[128];
int logfd;
/*
static void read_config()
{
	struct ccl_t config;
	const struct ccl_pair_t *iter;
	config.comment_char = '#';
	config.sep_char = '=';
	config.str_char = '"';
	ccl_parse(&config, "../conf/SmsNotification.ccl");
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
*/

static void my_init()
{
	//read_config();
	logfd=open(logfile,O_WRONLY|O_APPEND);
	if(logfd==-1)
	{
		printf("open logfile error!\n");
		exit(0);
	}
	
	//proclog(logfd,"starting...");
}
int __ns1__notifySmsDeliveryReceipt(struct soap *soap, struct ns2__notifySmsDeliveryReceipt *ns2__notifySmsDeliveryReceipt, struct ns2__notifySmsDeliveryReceiptResponse* ns2__notifySmsDeliveryReceiptResponse)
{
	sprintf(logbuf,"[notifySmsDeliveryReceipt]corr[%s]status[%d]",ns2__notifySmsDeliveryReceipt->correlator,ns2__notifySmsDeliveryReceipt->deliveryStatus->deliveryStatus);
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
	corr 2
	status 30
	*/
	char buf[256];
	strcpy(buf,"3");
	strcpy(buf+2,ns2__notifySmsDeliveryReceipt->correlator);
	char tmp[4];
	sprintf(tmp,"%d",ns2__notifySmsDeliveryReceipt->deliveryStatus->deliveryStatus);
	strcpy(buf+30,tmp);
	
	write(fd,buf,sizeof(buf));
	close(fd);
	
	
	return SOAP_OK;
}
 
int __ns1__notifySmsReception(struct soap *soap, struct ns2__notifySmsReception *ns2__notifySmsReception, struct ns2__notifySmsReceptionResponse* ns2__notifySmsReceptionResponse)
{
	
	//printf("1:%s\n",soap->header->ns4__NotifySOAPHeader->spId);
	//printf("2:%s\n",ns2__notifySmsReception->message->message);
	char gbcontent[512];
	memset(gbcontent,0,sizeof(gbcontent));
	to_gb(ns2__notifySmsReception->message->message,gbcontent);
	
	sprintf(logbuf,"[Reception]regId[%s]message[%s]sender[%s]servnumber[%s]linkid[%s]",
								ns2__notifySmsReception->registrationIdentifier,
								gbcontent,
								//ns2__notifySmsReception->message->message,
								ns2__notifySmsReception->message->senderAddress,
								ns2__notifySmsReception->message->smsServiceActivationNumber,
								soap->header->ns4__NotifySOAPHeader->linkId);
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
	regID 2
	message 30 
	sender 180
	servnumber 200
	linkid 220
	*/
	char buf[256];
	strcpy(buf,"1");
	if(ns2__notifySmsReception->registrationIdentifier)
		strcpy(buf+2,ns2__notifySmsReception->registrationIdentifier);
	if(ns2__notifySmsReception->message->message,gbcontent)
		//strcpy(buf+30,ns2__notifySmsReception->message->message);
		strcpy(buf+30,gbcontent);
	if(ns2__notifySmsReception->message->senderAddress)
		strcpy(buf+180,ns2__notifySmsReception->message->senderAddress+4);
	if(ns2__notifySmsReception->message->smsServiceActivationNumber)
		strcpy(buf+200,ns2__notifySmsReception->message->smsServiceActivationNumber+4);
	if(soap->header->ns4__NotifySOAPHeader->linkId)
		strcpy(buf+220,soap->header->ns4__NotifySOAPHeader->linkId);
	
	write(fd,buf,sizeof(buf));
	close(fd);
	
	


	return SOAP_OK;
}
int main(int argc, char **argv)
{ 
	SOAP_SOCKET m, s; /* master and slave sockets */
	my_init();
	struct soap soap;
	soap_init(&soap);
	soap_set_mode(&soap, SOAP_C_UTFSTRING);
	soap_serve(&soap);
	return 0;
}
