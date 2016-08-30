import requests

email='test2@aaaiusc.com'

url = 'http://aaaiusc.com/games/game1.php'
payload = {'email' : email}
r = requests.post(url, data=payload)
numChar=int(r.text)
counter=0
trys=[['123456','123475','234571','650392','975631'],['1239456','1230475','6159734','6387210','9756310'],['12349756','12348975','83956471','60538721','19752630']]
while 1:
	payload = {'email' : email , 'answer' : trys[numChar-6][counter]}
	r = requests.post(url, data=payload)
	#print r.text
	if(r.text.find('Success')!=-1):
		payload = {'email' : email}
		r = requests.post(url, data=payload)
		numChar=int(r.text)
		counter=0
		continue
	a=r.text[1]
	b=r.text[3]
	print a,b
	counter=counter+1;