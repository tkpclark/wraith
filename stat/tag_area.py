import sys
import os
import time
from Mydb import mysql
import logging
from logging.handlers import RotatingFileHandler
def tag_area():
    
    print "loading mobiledict..."
    mobile_dict = load_mobile_dict()
    if mobile_dict is not False:
        print "mobiledict loaded:", len(mobile_dict)
        
        
    sql = "select id, phone_number from wraith_mt where LENGTH(area)=0 or area is NULL or area='' limit 100"
    
    
    while True:
        result = mysql.queryAll(sql);
        
        if(len(result)==0):
            print 'no result'
            time.sleep(5)
            continue
        else:
            for row in result:
                if(mobile_dict.has_key(row['phone_number'][:7])):
                    area = mobile_dict[row['phone_number'][:7]]['area']
                else:
                    area = '0000'
                    
                update_sql = "update wraith_mt set area = '%s' where id = '%s'" % (area, row['id'])
                print update_sql
                mysql.query(update_sql)
def init_env():
    
    #chdir
    os.chdir(sys.path[0])
    
    '''
    #init logging
    logfile = '/home/app/wraith/logs/controller/controller.log'
    Rthandler = RotatingFileHandler(logfile, maxBytes=10*1024*1024,backupCount=5)
    formatter = logging.Formatter('[%(asctime)s][%(levelname)s][1.00]:  %(message)s - %(filename)s:%(lineno)d')
    Rthandler.setFormatter(formatter)
    logger=logging.getLogger()
    logger.addHandler(Rthandler)
    logger.setLevel(logging.NOTSET)
    '''
def load_mobile_dict():
    m_dict = {}
   
    fd = open('../../data/mobiledict.config', 'rb')
    for fitem in fd.readlines():
        if len(fitem.split('\t')) == 3:
            num, province, area = fitem.split('\t')
            if m_dict.has_key(num):
                print "mobiledict.config double key:", num
            else:
                m_dict[num] = {}
                m_dict[num]['province'] = province.strip()
                m_dict[num]['area'] = area.strip()
        else:
            print "mobiledict.config err:", fitem
    fd.close()
    return m_dict

def main():
    init_env()
    tag_area()
if __name__ == "__main__":
    main()
