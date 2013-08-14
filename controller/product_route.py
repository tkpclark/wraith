from Mydb import mysql
class Product_route:
    
    products = []
    __t__ = '*'
    
    def load_products(self):
        sql = "select * from wraith_products"
        self.products = mysql.queryAll(sql);
        
    def __probably_match__(self,gwid, sp_number, message):
            
        ##
        i = 0
        while True:
            i += 1
            if(len(sp_number) <= 9 and sp_number.find(self.__t__)>0):
                break
            if(i != 1):
                sp_number = self.__extend_prob__(sp_number)
            _message = message
            j=0
            while True:
                j += 1
                if(_message == self.__t__):
                    break
                if(j != 1):
                    _message = self.__extend_prob__(_message)               
                product = self.__search__(gwid, sp_number, _message)
                if(product != False):
                    return product
            
        return False
    
    def match(self, gwid, sp_number, message):
        
        #there is  "tel:" before ctcc spnumber, need to remove it
        if(sp_number[0:4] == 'tel:'):
            sp_number = sp_number[4:]
            
        #accurately first    
        product = self.__search__(gwid, sp_number, message)
        if(product != False):
            return product
        #probably
        product = self.__probably_match__(gwid, sp_number, message)
        if(product != False):
            return product
        
        return False
    
    def __search__(self,gwid, sp_number, message):
        for product in self.products:
            #print "'%s','%s' | '%s','%s'"%(sp_number, message.lower(), product['sp_number'], product['message'].lower())
            if(product['gwid'] == gwid) and (product['sp_number'] == sp_number) and (product['message'].lower() == message.lower()):
                return product
        return False
   
    def __extend_prob__(self,str):
        if(str.find(self.__t__) < 0):
            str += self.__t__
        else:
            str = str[0:-2] + self.__t__
        return str
    
    
