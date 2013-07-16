import Mydb
import time
import urllib2
import logging
from logging.handlers import RotatingFileHandler


#import ExPosition
#import LoadMoData
#import prod_route

host = '202.85.209.109'
user = 'wraith'
password = 'tengyewudi2012@)!@'
logfile = '/home/app/logs/controller/controller.log'

mysql = Mydb.Mydb(host, user, password)


class MoData:
    
    def get_deal_pos(self):
        return 1
    def set_deal_pos(self):
        return True
    def read_data(self):
        global mysql
        sql = "select * from wraith_mo where id > '%d'"%(self.get_deal_pos())
        data = mysql.queryAll(sql);
        return data

def prod_route(cmd,spnumber,gwid):
    url = "http://202.85.209.109/wraith/app.php"
    logging.debug('got' + cmd)
    return url
    
def exec_app(appurl):
    opener = urllib2.build_opener()
    try:
        file = opener.open(appurl)
        resp = file.read()
    except:
        return False
    
    
    
    if(resp == 'ok'):
        return True
    else:
        return False
    
def init_env():
    
    #init logging
    Rthandler = RotatingFileHandler(logfile, maxBytes=10*1024*1024,backupCount=5)
    formatter = logging.Formatter('[%(asctime)s][%(levelname)s][1.00]:  %(message)s - %(filename)s:%(lineno)d')
    Rthandler.setFormatter(formatter)
    logger=logging.getLogger()
    logger.addHandler(Rthandler)
    logger.setLevel(logging.NOTSET)
    
def main():
    
    init_env()
    mysql.selectDb('wraith')
    mo_data = MoData() 
    
    while True:
        data = mo_data.read_data()
        for record in data:
            logging.debug(record['phone_number'])
            app_url = prod_route(record['message'], record['sp_number'], record['gwid'])
            if(exec_app(app_url) == True):
                logging.debug('exec_app ok') 
            else:
                logging.debug('exec_app failed') 
                
        time.sleep(10)
if __name__ == "__main__":
    main()
    


