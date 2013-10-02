#encoding:utf-8
import sys
import os
import redis
from Mydb import mysql
import datetime

class Visit_limit:
    __v_dict__ = []
    r = False
    def __init__(self):
        self.r = redis.StrictRedis(host='localhost', port=6379, db=0)
    def load_dict(self):
        sql = "select * from wraith_visit_limit"
        self.__v_dict__ = mysql.queryAll(sql)
        #print self.__v_dict__
    #def get_user_visit_count(self,phone_number,province,product_id):
    def set_user_visit_count_daily(self,phone_number,product_id,province):
        now = datetime.datetime.now()
        day = now.strftime('%Y%m%d')
        
        
        ###daily
        key = '%s_%s_%s_%s' % (product_id,province,phone_number,day)
        #self.r.INCR(key)
        if(self.r.exists(key)==False):
            self.r.setex(key,86400,1)
            return 1
        else:
            return self.r.incr(key)
            
            
        ###monthly
    
    def set_user_visit_count_monthly(self,phone_number,product_id,province):
        now = datetime.datetime.now()
        month = now.strftime('%Y%m')
        
        
        ###daily
        key = '%s_%s_%s_%s' % (product_id,province,phone_number,month)
        #self.r.INCR(key)
        if(self.r.exists(key)==False):
            self.r.setex(key,2764800,1)
            return 1
        else:
            return self.r.incr(key)
            
            
        ###monthly   
    def get_user_visit_limit(self,product_id,province):
        
        #exactly
        for record in self.__v_dict__:
            #print record['product_id'],record['province']
            #print product_id,province
            if( (record['product_id'] == product_id) and (record['province'] == province)):
                 return (record['daily_count'],record['monthly_count'])
            
        #default of a product
        for record in self.__v_dict__:
            #print record['product_id'],record['province']
            #print product_id,province
            if( (record['product_id'] == product_id) and (record['province'] == '默认')):
                return (record['daily_count'],record['monthly_count'])
        
        #eventually default
        return (20,100);
    def is_arrive_limit(self,phone_number, product_id, province):
        
        visit_limit_daily,visit_limit_monthly=self.get_user_visit_limit(product_id,province)
        
        #daily_limit check
        visit_count_dayily=self.set_user_visit_count_daily(phone_number, product_id, province)
        visit_count_monthly=self.set_user_visit_count_monthly(phone_number, product_id, province)
        
        
        if((int)(visit_count_dayily) >= (int)(visit_limit_daily)):
            return True
        if((int)(visit_count_monthly) >= (int)(visit_limit_monthly)):
            return True
        
        return False
        
        
        
if __name__ == "__main__":
    visit_limit = Visit_limit()
    visit_limit.load_dict()
    #print visit_limit.get_user_visit_limit('3','山东'),
    #print visit_limit.set_user_visit_count_daily('13810002000','1','山东'),
    #print visit_limit.set_user_visit_count_monthly('13810002000','1','山东')
    
    #print visit_limit.is_arrive_limit('13810002000','1','山东')