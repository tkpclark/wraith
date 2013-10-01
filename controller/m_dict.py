#encoding:utf-8
class Mobile_dict:
    
    __m_dict__ = {}
    __t__ = ','
    def load_mobile_dict(self):
    
        fd = open('/home/app/wraith/conf/code-utf8.dict', 'rb')
        for fitem in fd.readlines():
            if len(fitem.split(self.__t__)) == 3:
                province, area, num = fitem.split(',')
                province = province.strip()
                area =  area.strip()
                num = num.strip()
    
                if self.__m_dict__.has_key(num):
                    #print "mobiledict.config double key:", num
                    pass
                else:
                    self.__m_dict__[num] = {}
                    self.__m_dict__[num]['province'] = province
                    self.__m_dict__[num]['area'] = area
            else:
                print "mobiledict.config err:", fitem
        fd.close()
    def get_mobile_area(self, phone_code):
        if self.__m_dict__.has_key(phone_code):
            #print self.__m_dict__[phone_code]['area']
            return (self.__m_dict__[phone_code]['province'],self.__m_dict__[phone_code]['area'])
        else:
            return ('未知','未知')