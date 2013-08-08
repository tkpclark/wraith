#include "soapH.h"
#include "SmsNotificationBinding.nsmap"
int main(int argc, char **argv)
{
	SOAP_SOCKET m, s; /* master and slave sockets */
	struct soap soap;
	soap_init(&soap);


	//header
	char *linkid="999999999999999";
	struct ns4__NotifySOAPHeader NotifySOAPHeader;
	NotifySOAPHeader.linkId=linkid;s
	soap.header = (struct SOAP_ENV__Header *)soap_malloc(&soap, sizeof(struct SOAP_ENV__Header));
	soap.header->ns4__NotifySOAPHeader=&NotifySOAPHeader;


	/////////////////


	const char *soap_endpoint="http://202.85.209.109/services/SmsNotification";

	char *message="hello,i am clark!";
	char *senderAddress="13910002000";
	char *smsServiceActivationNumber="10668888";

	char *registrationIdentifier="registrationIdentifier";

	struct ns3__SmsMessage SmsMessage;
	struct ns2__notifySmsReception NotifySmsReception;
	SmsMessage.message=message;
	SmsMessage.senderAddress=senderAddress;
	SmsMessage.smsServiceActivationNumber=smsServiceActivationNumber;

	NotifySmsReception.registrationIdentifier=registrationIdentifier;
	NotifySmsReception.message=&SmsMessage;

	struct ns2__notifySmsReceptionResponse notifySmsReceptionResponse;

	soap_call___ns1__notifySmsReception(&soap, soap_endpoint, NULL, &NotifySmsReception, &notifySmsReceptionResponse);
	return 0;
}
