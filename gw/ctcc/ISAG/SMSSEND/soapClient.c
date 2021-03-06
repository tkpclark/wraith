/* soapClient.c
   Generated by gSOAP 2.8.15 from ctcc_sms_send_service_2_1.h

Copyright(C) 2000-2013, Robert van Engelen, Genivia Inc. All Rights Reserved.
The generated code is released under ONE of the following licenses:
GPL or Genivia's license for commercial use.
This program is released under the GPL with the additional exemption that
compiling, linking, and/or using OpenSSL is allowed.
*/

#if defined(__BORLANDC__)
#pragma option push -w-8060
#pragma option push -w-8004
#endif
#include "soapH.h"
#ifdef __cplusplus
extern "C" {
#endif

SOAP_SOURCE_STAMP("@(#) soapClient.c ver 2.8.15 2013-07-16 07:27:49 GMT")


SOAP_FMAC5 int SOAP_FMAC6 soap_call___ns1__sendSms(struct soap *soap, const char *soap_endpoint, const char *soap_action, struct ns2__sendSms *ns2__sendSms, struct ns2__sendSmsResponse *ns2__sendSmsResponse)
{	struct __ns1__sendSms soap_tmp___ns1__sendSms;
	if (soap_endpoint == NULL)
		soap_endpoint = "http://localhost:9080/SendSmsService/services/SendSms";
	if (soap_action == NULL)
		soap_action = "";
	soap->encodingStyle = NULL;
	soap_tmp___ns1__sendSms.ns2__sendSms = ns2__sendSms;
	soap_begin(soap);
	soap_serializeheader(soap);
	soap_serialize___ns1__sendSms(soap, &soap_tmp___ns1__sendSms);
	if (soap_begin_count(soap))
		return soap->error;
	if (soap->mode & SOAP_IO_LENGTH)
	{	if (soap_envelope_begin_out(soap)
		 || soap_putheader(soap)
		 || soap_body_begin_out(soap)
		 || soap_put___ns1__sendSms(soap, &soap_tmp___ns1__sendSms, "-ns1:sendSms", NULL)
		 || soap_body_end_out(soap)
		 || soap_envelope_end_out(soap))
			 return soap->error;
	}
	if (soap_end_count(soap))
		return soap->error;
	if (soap_connect(soap, soap_url(soap, soap_endpoint, NULL), soap_action)
	 || soap_envelope_begin_out(soap)
	 || soap_putheader(soap)
	 || soap_body_begin_out(soap)
	 || soap_put___ns1__sendSms(soap, &soap_tmp___ns1__sendSms, "-ns1:sendSms", NULL)
	 || soap_body_end_out(soap)
	 || soap_envelope_end_out(soap)
	 || soap_end_send(soap))
		return soap_closesock(soap);
	if (!ns2__sendSmsResponse)
		return soap_closesock(soap);
	soap_default_ns2__sendSmsResponse(soap, ns2__sendSmsResponse);
	if (soap_begin_recv(soap)
	 || soap_envelope_begin_in(soap)
	 || soap_recv_header(soap)
	 || soap_body_begin_in(soap))
		return soap_closesock(soap);
	soap_get_ns2__sendSmsResponse(soap, ns2__sendSmsResponse, "ns2:sendSmsResponse", "ns2:sendSmsResponse");
	if (soap->error)
		return soap_recv_fault(soap, 0);
	if (soap_body_end_in(soap)
	 || soap_envelope_end_in(soap)
	 || soap_end_recv(soap))
		return soap_closesock(soap);
	return soap_closesock(soap);
}

SOAP_FMAC5 int SOAP_FMAC6 soap_call___ns1__sendSmsLogo(struct soap *soap, const char *soap_endpoint, const char *soap_action, struct ns2__sendSmsLogo *ns2__sendSmsLogo, struct ns2__sendSmsLogoResponse *ns2__sendSmsLogoResponse)
{	struct __ns1__sendSmsLogo soap_tmp___ns1__sendSmsLogo;
	if (soap_endpoint == NULL)
		soap_endpoint = "http://localhost:9080/SendSmsService/services/SendSms";
	if (soap_action == NULL)
		soap_action = "";
	soap->encodingStyle = NULL;
	soap_tmp___ns1__sendSmsLogo.ns2__sendSmsLogo = ns2__sendSmsLogo;
	soap_begin(soap);
	soap_serializeheader(soap);
	soap_serialize___ns1__sendSmsLogo(soap, &soap_tmp___ns1__sendSmsLogo);
	if (soap_begin_count(soap))
		return soap->error;
	if (soap->mode & SOAP_IO_LENGTH)
	{	if (soap_envelope_begin_out(soap)
		 || soap_putheader(soap)
		 || soap_body_begin_out(soap)
		 || soap_put___ns1__sendSmsLogo(soap, &soap_tmp___ns1__sendSmsLogo, "-ns1:sendSmsLogo", NULL)
		 || soap_body_end_out(soap)
		 || soap_envelope_end_out(soap))
			 return soap->error;
	}
	if (soap_end_count(soap))
		return soap->error;
	if (soap_connect(soap, soap_url(soap, soap_endpoint, NULL), soap_action)
	 || soap_envelope_begin_out(soap)
	 || soap_putheader(soap)
	 || soap_body_begin_out(soap)
	 || soap_put___ns1__sendSmsLogo(soap, &soap_tmp___ns1__sendSmsLogo, "-ns1:sendSmsLogo", NULL)
	 || soap_body_end_out(soap)
	 || soap_envelope_end_out(soap)
	 || soap_end_send(soap))
		return soap_closesock(soap);
	if (!ns2__sendSmsLogoResponse)
		return soap_closesock(soap);
	soap_default_ns2__sendSmsLogoResponse(soap, ns2__sendSmsLogoResponse);
	if (soap_begin_recv(soap)
	 || soap_envelope_begin_in(soap)
	 || soap_recv_header(soap)
	 || soap_body_begin_in(soap))
		return soap_closesock(soap);
	soap_get_ns2__sendSmsLogoResponse(soap, ns2__sendSmsLogoResponse, "ns2:sendSmsLogoResponse", "ns2:sendSmsLogoResponse");
	if (soap->error)
		return soap_recv_fault(soap, 0);
	if (soap_body_end_in(soap)
	 || soap_envelope_end_in(soap)
	 || soap_end_recv(soap))
		return soap_closesock(soap);
	return soap_closesock(soap);
}

SOAP_FMAC5 int SOAP_FMAC6 soap_call___ns1__sendSmsRingtone(struct soap *soap, const char *soap_endpoint, const char *soap_action, struct ns2__sendSmsRingtone *ns2__sendSmsRingtone, struct ns2__sendSmsRingtoneResponse *ns2__sendSmsRingtoneResponse)
{	struct __ns1__sendSmsRingtone soap_tmp___ns1__sendSmsRingtone;
	if (soap_endpoint == NULL)
		soap_endpoint = "http://localhost:9080/SendSmsService/services/SendSms";
	if (soap_action == NULL)
		soap_action = "";
	soap->encodingStyle = NULL;
	soap_tmp___ns1__sendSmsRingtone.ns2__sendSmsRingtone = ns2__sendSmsRingtone;
	soap_begin(soap);
	soap_serializeheader(soap);
	soap_serialize___ns1__sendSmsRingtone(soap, &soap_tmp___ns1__sendSmsRingtone);
	if (soap_begin_count(soap))
		return soap->error;
	if (soap->mode & SOAP_IO_LENGTH)
	{	if (soap_envelope_begin_out(soap)
		 || soap_putheader(soap)
		 || soap_body_begin_out(soap)
		 || soap_put___ns1__sendSmsRingtone(soap, &soap_tmp___ns1__sendSmsRingtone, "-ns1:sendSmsRingtone", NULL)
		 || soap_body_end_out(soap)
		 || soap_envelope_end_out(soap))
			 return soap->error;
	}
	if (soap_end_count(soap))
		return soap->error;
	if (soap_connect(soap, soap_url(soap, soap_endpoint, NULL), soap_action)
	 || soap_envelope_begin_out(soap)
	 || soap_putheader(soap)
	 || soap_body_begin_out(soap)
	 || soap_put___ns1__sendSmsRingtone(soap, &soap_tmp___ns1__sendSmsRingtone, "-ns1:sendSmsRingtone", NULL)
	 || soap_body_end_out(soap)
	 || soap_envelope_end_out(soap)
	 || soap_end_send(soap))
		return soap_closesock(soap);
	if (!ns2__sendSmsRingtoneResponse)
		return soap_closesock(soap);
	soap_default_ns2__sendSmsRingtoneResponse(soap, ns2__sendSmsRingtoneResponse);
	if (soap_begin_recv(soap)
	 || soap_envelope_begin_in(soap)
	 || soap_recv_header(soap)
	 || soap_body_begin_in(soap))
		return soap_closesock(soap);
	soap_get_ns2__sendSmsRingtoneResponse(soap, ns2__sendSmsRingtoneResponse, "ns2:sendSmsRingtoneResponse", "ns2:sendSmsRingtoneResponse");
	if (soap->error)
		return soap_recv_fault(soap, 0);
	if (soap_body_end_in(soap)
	 || soap_envelope_end_in(soap)
	 || soap_end_recv(soap))
		return soap_closesock(soap);
	return soap_closesock(soap);
}

SOAP_FMAC5 int SOAP_FMAC6 soap_call___ns1__getSmsDeliveryStatus(struct soap *soap, const char *soap_endpoint, const char *soap_action, struct ns2__getSmsDeliveryStatus *ns2__getSmsDeliveryStatus, struct ns2__getSmsDeliveryStatusResponse *ns2__getSmsDeliveryStatusResponse)
{	struct __ns1__getSmsDeliveryStatus soap_tmp___ns1__getSmsDeliveryStatus;
	if (soap_endpoint == NULL)
		soap_endpoint = "http://localhost:9080/SendSmsService/services/SendSms";
	if (soap_action == NULL)
		soap_action = "";
	soap->encodingStyle = NULL;
	soap_tmp___ns1__getSmsDeliveryStatus.ns2__getSmsDeliveryStatus = ns2__getSmsDeliveryStatus;
	soap_begin(soap);
	soap_serializeheader(soap);
	soap_serialize___ns1__getSmsDeliveryStatus(soap, &soap_tmp___ns1__getSmsDeliveryStatus);
	if (soap_begin_count(soap))
		return soap->error;
	if (soap->mode & SOAP_IO_LENGTH)
	{	if (soap_envelope_begin_out(soap)
		 || soap_putheader(soap)
		 || soap_body_begin_out(soap)
		 || soap_put___ns1__getSmsDeliveryStatus(soap, &soap_tmp___ns1__getSmsDeliveryStatus, "-ns1:getSmsDeliveryStatus", NULL)
		 || soap_body_end_out(soap)
		 || soap_envelope_end_out(soap))
			 return soap->error;
	}
	if (soap_end_count(soap))
		return soap->error;
	if (soap_connect(soap, soap_url(soap, soap_endpoint, NULL), soap_action)
	 || soap_envelope_begin_out(soap)
	 || soap_putheader(soap)
	 || soap_body_begin_out(soap)
	 || soap_put___ns1__getSmsDeliveryStatus(soap, &soap_tmp___ns1__getSmsDeliveryStatus, "-ns1:getSmsDeliveryStatus", NULL)
	 || soap_body_end_out(soap)
	 || soap_envelope_end_out(soap)
	 || soap_end_send(soap))
		return soap_closesock(soap);
	if (!ns2__getSmsDeliveryStatusResponse)
		return soap_closesock(soap);
	soap_default_ns2__getSmsDeliveryStatusResponse(soap, ns2__getSmsDeliveryStatusResponse);
	if (soap_begin_recv(soap)
	 || soap_envelope_begin_in(soap)
	 || soap_recv_header(soap)
	 || soap_body_begin_in(soap))
		return soap_closesock(soap);
	soap_get_ns2__getSmsDeliveryStatusResponse(soap, ns2__getSmsDeliveryStatusResponse, "ns2:getSmsDeliveryStatusResponse", "ns2:getSmsDeliveryStatusResponse");
	if (soap->error)
		return soap_recv_fault(soap, 0);
	if (soap_body_end_in(soap)
	 || soap_envelope_end_in(soap)
	 || soap_end_recv(soap))
		return soap_closesock(soap);
	return soap_closesock(soap);
}

#ifdef __cplusplus
}
#endif

#if defined(__BORLANDC__)
#pragma option pop
#pragma option pop
#endif

/* End of soapClient.c */
