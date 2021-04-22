import time
import urllib2

def measure():
    try:
        file1 = open("data.txt","r")
        data = file1.readlines()
        #print data
        file1.close()

        d = str(data)

        d=d.replace('[', '')
        d=d.replace('\'', '')
        d=d.replace(']', '')

        x = d.split(",")

        f=x[0]
        hb1=x[1]
        sp1=x[2]

        if f == "0":
            f = "1"
        if hb1 == "0":
            hb1 = "1"
        if sp1 == "0":
            sp1 = "1"
        print (f)
        print (hb1)
        print (sp1)

        data = "temperature="+str(f)+"&spo2="+str(sp1)+"&pulse="+str(hb1)
        data2 = ("http://myproject.org.in/patientmonitor/save.php?"+str(data))
        #print(data)
        #print(data2)
        webUrl = urllib2.urlopen(data2)
        print "result code: " + str(webUrl.getcode())
        data3 = webUrl.read()
	print data3

    except:
        print("connection failed")

if __name__ == "__main__":
    while True:
        measure()
        time.sleep(5)