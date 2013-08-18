import sys
import os
import time
from Mydb import mysql
import logging
import datetime
from logging.handlers import RotatingFileHandler
def stat():
    d = datetime.datetime.now()
    onehour = datetime.timedelta(hours=1)
    d -= onehour
    stat_hour = d.strftime("%Y-%m-%d:%H")
    print "stat hour: " + stat_hour
    db_stat_hour = "DATE_FORMAT(in_time,'%Y-%m-%d:%H')"
    
    
    #delete old days if
    sql = "delete from wraith_statistic where stat_time = '%s'" % (stat_hour)
    print sql
    mysql.query(sql)
    
    ##group
    sql = "select gwid,sp_number,product_id,product_code,amount,count(*) as num from `wraith_mt` where %s='%s' group by gwid,sp_number,product_id,product_code" % (db_stat_hour,stat_hour)
    print sql
    result = mysql.queryAll(sql)
    if(len(result)):
        for row in result:
            where_clause = " %s='%s' and gwid='%s' and sp_number='%s' and product_id='%s' and product_code='%s' and amount='%s'"%(db_stat_hour,stat_hour,row['gwid'],row['sp_number'],row['product_id'],row['product_code'],row['amount'])
            print "num: " + row['num']
            #count sucessful record number:
            csql = "select count(*) as r from wraith_mt where %s and (report = 4 or report  ='DELIVRD') " % (where_clause)
            print csql
            cresult = mysql.queryAll(csql)
            success_num = cresult[0]['r']
        
            
            #count other
            
            
            #insert
            csql = "insert into wraith_statistic(gwid,sp_number,product_id,product_code,amount,stat_time,num,success_num)values('%s','%s','%s','%s','%s','%s','%s','%s')" % (row['gwid'],row['sp_number'],row['product_id'],row['product_code'],row['amount'],stat_hour,row['num'],success_num)
            print csql
            mysql.query(csql)
def init_env():
    
    #chdir
    os.chdir(sys.path[0])
    
    #init logging
    '''
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
    stat()
    
if __name__ == "__main__":
    main()
