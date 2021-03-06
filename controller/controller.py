#encoding:utf-8
import sys
import os
from Mydb import mysql
import time
import urllib
import urllib2
import logging
from logging.handlers import RotatingFileHandler
import json
from product_route import *
from m_dict import *
from blklist import *
from visit_limit import *
import datetime


class MoData:
    
    __seq_file__ = "id.seq"
    def get_deal_pos(self):
        f = open(self.__seq_file__)
        id = f.read()
        id = id.strip()
        f.close()
        if(id.isdigit()):
            return id
        else:
            print("not digtal in %s"%(self.__seq_file__))
            sys.exit(1)
    def set_deal_pos(self, id):
        f = open(self.__seq_file__,'w+')
        f.write(id)
        f.close()
    def read_data(self):
        sql = "select * from wraith_mo where id > '%s' limit 1"%(self.get_deal_pos())
        data = mysql.queryAll(sql);
        return data
    
def exec_app(appurl):
    opener = urllib2.build_opener()
    try:
        file = opener.open(appurl)
        resp = file.read()
        return True
    except:
        logging.info("failed:"+appurl)
        return False
   
''' 
def check_phone_visit_count(phone_number):
    now = datetime.datetime.now()
    day = now.strftime('%Y-%m-%d')
    month = now.strftime('%Y-%m')
    
    ##look whether reach day_max
    sql = "select visit_count from wraith_visit_count where phone_number='%s' and visit_date='%s'"%(phone_number,day)
    logging.info(sql);
    data = mysql.queryAll(sql);
    if(mysql.rowcount()==0):
       sql = "insert into wraith_visit_count (phone_number, visit_date, visit_count) values('%s','%s','1')"%(phone_number, day)
       mysql.queryAll(sql)
    else:
        if(print data[0]['visit_count']>
    ##look whether reach month max
''' 


    
def init_env():
    
    #chdir
    os.chdir(sys.path[0])
    
    #init logging
    logfile = '/home/app/wraith/logs/controller/controller.log'
    Rthandler = RotatingFileHandler(logfile, maxBytes=10*1024*1024,backupCount=5)
    formatter = logging.Formatter('[%(asctime)s][%(levelname)s][1.00]:  %(message)s - %(filename)s:%(lineno)d')
    Rthandler.setFormatter(formatter)
    logger=logging.getLogger()
    logger.addHandler(Rthandler)
    logger.setLevel(logging.NOTSET)
    
    #init product_route
    global product_route
    product_route = Product_route()
    product_route.load_products()
    
    global blklist
    blklist = Blklist()
    blklist.load_blklist()
    
    global mobile_dict
    mobile_dict = Mobile_dict()
    mobile_dict.load_mobile_dict()
    
    global visit_limit
    visit_limit = Visit_limit()
    visit_limit.load_dict()
    
def main():
    
    init_env()
    mo_data = MoData() 
    
    while True:
        data = mo_data.read_data()
        #print (len(data))
        if(len(data) == 0):
            time.sleep(1)
            continue

        for record in data:
            ########logging.debug(json.dumps(record))
            logging.info(record)
            mo_data.set_deal_pos(record['id'])
            
            
            ########linkisok?
            if(record['linkid'].isdigit() == False):
                logging.info('!!!linkid abnormal:' + record['linkid'])
                continue
            
            
            ##########blk list check
            #logging.info("matching..."+record['phone_number'])
            if(blklist.match(record['phone_number'])):
                logging.info('!!!blklist:' + record['phone_number'])
                continue
            
            
         
            #######match a product            
            product = product_route.match(record['gwid'], record['sp_number'], record['message'])
            if(product == False):
                logging.fatal('!!! %s + %s + %s not match',record['gwid'], record['sp_number'], record['message'])   
                continue
            
            
            
            
            logging.info('match product:' + product['id'])
            
           
            
            ########append product info for app
            record['product_seq'] = product['id']
            record['product_id'] = product['product_id']
            record['product_code'] = product['product_code']
            record['amount'] = product['amount']
            record['province'],record['area'] = mobile_dict.get_mobile_area(record['phone_number'])
            #record['default_msg']=product['default_msg']
            record['default_msg']=product_route.get_random_content(product['id'])
            record['allow_province']=product['allow_province']
            
            
            
            ########check visit count 
            limit_flag = visit_limit.is_arrive_limit(record['phone_number'],product['id'],record['province'],record['gwid'])
            if(limit_flag > 0):
                logging.info('visit limit! phone_number:%s, pid:%s, product_id:%s, gwid:%s, limit flag:%d',record['phone_number'],record['id'],record['product_id'],record['gwid'],limit_flag)
                continue
            
            
            
            ########check allow province  
            if record['province'] not in record['allow_province']:
                logging.info('phone is not in allow provinces! province:%s',record['province'])
                continue
            
            '''
            if record['area'] in '深圳 汕头 邯郸 石家庄 扬州 镇江 烟台 怀化':
                logging.info('phone is in forbidden area! area:%s',record['area'])
                continue
            '''
            if(record['province']+'@'+record['area'] in product['forbidden_area']):
                logging.info('phone is in forbidden area! area:%s@%s',record['province'],record['area'])
                continue
           
            ########
            ########
            ########all check is ok,do it!
            app_url = product['url'] + 'record=' + urllib.quote_plus(json.dumps(record))
            logging.info(app_url)
            if(exec_app(app_url) == False):
                logging.fatal('failed to visit [%s]', app_url)
                    
                
            
            
            #time.sleep(10)
if __name__ == "__main__":
    main()
    


