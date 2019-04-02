https://sip27.webvoipphone.com/mvapireq/?
apientry=sendsms
&phone1=MIZUTEST
&phone2=778-838-8668
&message=
&authkey=14150857
&authid=iwb-saas-api-test
&now=1550662268
&authpwd=gsdoguh4u54kl5izg34jzf535uzf35


Note: You can generate working examples for your server from MManage -> Tools menu -> Configure -> Client Config -> Generate
API examples menu.
API test (this is the simplest example without authentication requirements):
<b>http://domain.com/mvapireq/?apientry=apitest1</b>

Request user credit if set as public with no apiv2key set (not recommended in production):
?apientry=balance&authid=USRNAME

Request user credit with password in clear text (not recommended in production):
?apientry=balance&authkey=KEY&authid=USRNAME&authpwd=PASSWORD

Request user credit with proper authentication (recommended):
?apientry=balance&authkey=KEY&authid=USRNAME&authmd5=MD5

Initiate callback:
?apientry=callback&authkey=KEY&authid=USRNAME&authmd5=MD5&anum=PHONE1

Initiate phone to phone call:
?apientry=p2p&authkey=KEY&authid=USRNAME&authmd5=MD5&anum=PHONE1&bnum=PHONE2

Sending SMS message:
?apientry=sms&authkey=KEY&authid=USRNAME&authmd5=MD5&anum=PHONE1&bnum=PHONE2

&txt=TEXT
Request rate to destination:
?apientry=rating&authkey=KEY&authid=USRNAME&authmd5=MD5&anum=PREFIX_OR_NUMBER

Recharge with PIN code:
?apientry=charge&authkey=KEY&authid=USRNAME&authmd5=MD5&anum=PINCODE&now

Add credit: (from trusted IP only)
?apientry=addcredit&authkey=KEY&authid=USRNAME&authmd5=MD5&pin=USERNAME&credit=A

MMOUNT
With raw TCP or UDP you can send in the following format (terminated with a new line if TCP):
/mvapireq/?apientry=apitest&authkey=KEY&authid=USRNAME&authmd5=MD5
With websocket, you have to use the uri with the “mvstwebsock” parameter such as:
ws://yourserveraddress/mvstwebsock/
Then send the request within the websocket stream as it would be TCP (the rest of the request line).