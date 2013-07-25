from Mydb import mysql
import time
import urllib
import urllib2
import logging
from logging.handlers import RotatingFileHandler
import json
from product_route import *




class MoData:
    
    def get_deal_pos(self):
        return 1
    def set_deal_pos(self):
        return True
    def read_data(self):
        sql = "select * from wraith_mo where id > '%d' limit 1"%(self.get_deal_pos())
        data = mysql.queryAll(sql);
        return data
    
def exec_app(appurl):
    opener = urllib2.build_opener()
    try:
        file = opener.open(appurl)
        resp = file.read()
    except:
        logging.info("failed:"+appurl)
        return False
    
    
    
    if(resp == 'ok'):
        return True
    else:
        return False
    
def init_env():
    
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
        for record in data:
            #logging.debug(json.dumps(record))
            app_url = product_route.match(record['gwid'], record['sp_number'], record['message'])
            
            if(app_url != False):
                app_url += '?record=' + urllib.quote_plus(json.dumps(record))
                logging.info(app_url)
                if(exec_app(app_url) == True):
                    pass
            else:
                logging.info('!!! %s + %s + %s not match',record['gwid'], record['sp_number'], record['message'])   
            time.sleep(10)
if __name__ == "__main__":
    main()
    


