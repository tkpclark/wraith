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
            #logging.debug(json.dumps(record))
            logging.info(record)
            product = product_route.match(record['gwid'], record['sp_number'], record['message'])
            
            if(product != False):
                
                logging.info('match product:' + product['id']);
                #append product info for app
                record['product_id'] = product['product_id']
                record['product_code'] = product['product_code']
                record['amount'] = product['amount']
                
                
                #print record;
                
                app_url = product['url'] + '?record=' + urllib.quote_plus(json.dumps(record))
                logging.info(app_url)
                if(exec_app(app_url) == True):
                    pass
                else:
                    logging.fatal('failed to visist [%s]', app_url)
            else:
                logging.fatal('!!! %s + %s + %s not match',record['gwid'], record['sp_number'], record['message'])   
            
            mo_data.set_deal_pos(record['id'])
           # time.sleep(10)
if __name__ == "__main__":
    main()
    


