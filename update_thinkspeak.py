import time
import thingspeak
import serial
import urllib2

channel_id = 1316413
write_key  = 'FJWLTFJBPFA7DFIR'
read_key   = 'LTFSGODQU2JYX8YR'

def measure(channel):
    try:
        file1 = open("data.txt","r")
        data = file1.readlines()
        print (data)
        file1.close()

        d = str(data)

        d=d.replace('[', '')
        d=d.replace('\'', '')
        d=d.replace(']', '')

        x = d.split(",")

        f=x[0]
        hb1=x[1]
        sp1=x[2]

        print (f)
        print (hb1)
        print (sp1)

        ecg = 0
        if ser.in_waiting > 0:
            data = ser.readline().decode('utf-8').rstrip()
            if data != '!':
                ecg = int(data)

        print (ecg)
	response = channel.update({'field1': f, 'field2': hb1, 'field3': sp1, 'field4': ecg})

        # read
        read = channel.get({})
        print("Read:", read)

        # open a connection to a URL using urllib
        url_msg = "http://myproject.org.in/Corona/save.php?temperature="+str(f)+"&respiration="+str(sp1)+"&heartrate="+str(hb1)

        webUrl  = urllib2.request.urlopen(url_msg)
        print ("result code: " + str(webUrl.getcode()))
        data = webUrl.read()
        print (data)

    except:
        print("connection failed")

if __name__ == "__main__":
    ser = serial.Serial('/dev/ttyS0', 9600, timeout=1)
    ser.flush()
    channel = thingspeak.Channel(id=channel_id, write_key=write_key, api_key=read_key)
    while True:
        measure(channel)
        # free account has an api limit of 15sec
        time.sleep(15)