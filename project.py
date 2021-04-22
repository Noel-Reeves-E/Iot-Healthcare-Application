import time
import os
import glob
os.system('modprobe w1-gpio')
os.system('modprobe w1-therm')
base_dir = '/sys/bus/w1/devices/'
device_folder = glob.glob(base_dir + '28*')[0]
device_file = device_folder + '/w1_slave'

import max30100
mx30 = max30100.MAX30100()
mx30.enable_spo2()

def read_temp_raw():
    f = open(device_file, 'r')
    lines = f.readlines()
    f.close()
    return lines

def read_temp():
    lines = read_temp_raw()
    while lines[0].strip()[-3:] != 'YES':
        time.sleep(0.2)
        lines = read_temp_raw()
    equals_pos = lines[1].find('t=')
    if equals_pos != -1:
        temp_string = lines[1][equals_pos+2:]
        temp_c = float(temp_string) / 1000.0
        temp_f = temp_c * 9.0 / 5.0 + 32.0
        return temp_c, temp_f



while True:
    c,f = read_temp()
    print c,"C,",f,"F"

    mx30.read_sensor()
    mx30.ir, mx30.red
    hb = int(mx30.ir / 100) / 2
    spo2 = int(mx30.red / 100) * 2
    hb1 = 1
    sp1 = 1

    if mx30.ir != mx30.buffer_ir :
        hb1 = hb
        print"Pulse:",hb
    if mx30.red != mx30.buffer_red:
        sp1 = spo2
        print"SPO2:",spo2

    L = [str(f)+","+str(hb1)+","+str(sp1)]

    file1 = open(r"data.txt","w")
    file1.writelines(L)
    file1.close()