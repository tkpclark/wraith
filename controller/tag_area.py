import sys
import os
import time
from Mydb import mysql
import logging
from logging.handlers import RotatingFileHandler
from m_dict import *
def tag_area():  
        
    sql = "select id, phone_number from wraith_mt_copy where LENGTH(province)=0 or province ='未知' or province='' limit 100"
    
    
    while True:
        result = mysql.queryAll(sql);
        
        if(len(result)==0):
            print 'finished!'
            return
        else:
            for row in result:
                province,area = mobile_dict.get_mobile_area(row['phone_number'][:7])
                update_sql = "update wraith_mt set province='%s',area = '%s' where id = '%s'" % (province,area, row['id'])
                print update_sql
                mysql.query(update_sql)
def init_env():
    
    #chdir
    os.chdir(sys.path[0])
    
    global mobile_dict
    mobile_dict = Mobile_dict()
    mobile_dict.load_mobile_dict()
    
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

def main():
    init_env()
    tag_area()
if __name__ == "__main__":
    main()
