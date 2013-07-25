from Mydb import mysql
class Product_route:
    
    products = []
    __t__ = '*'
    
    def load_products(self):
        sql = "select * from wraith_products"
        self.products = mysql.queryAll(sql);
        
    def __probably_match__(self,gwid, sp_number, message):
        while True:
            if(len(sp_number) == 9 and sp_number.find(self.__t__)>0):
                break
            sp_number = self.__extend_prob__(sp_number)
            _message = message
            while True:
                if(_message == self.__t__):
                    break
                _message = self.__extend_prob__(_message)               
                product = self.__search__(gwid, sp_number, _message)
                if(product != False):
                    return product
            
        return False
    
    def match(self, gwid, sp_number, message):
        #accurately first    
        product = self.__search__(gwid, sp_number, message)
        if(product != False):
            return product['url']
        #probably
        product = self.__probably_match__(gwid, sp_number, message)
        if(product != False):
            return product['url']
        
        return False
    
    def __search__(self,gwid, sp_number, message):
        print "'%s','%s'"%(sp_number, message)
        for product in self.products:
            if(product['gwid'] == gwid) and (product['sp_number'] == sp_number) and (product['message'] == message):
                return product
        return False
   
    def __extend_prob__(self,str):
        if(str.find(self.__t__) < 0):
            str += self.__t__
        else:
            str = str[0:-2] + self.__t__
        return str
    
    