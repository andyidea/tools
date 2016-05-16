# -*- coding: utf-8 -*-  
#---------------------------------------  
#   程序：百度搜索爬虫  
#   版本：0.1  
#   作者：LuoQi  
#   日期：2015-03-24  
#   语言：Python 2.7  
#   操作：输入带分页的地址，去掉最后面的数字，设置一下起始页数和终点页数。  
#   功能：下载对应页码内的所有页面并存储为txt文件。  
#---------------------------------------  
import string  
import urllib  
import urllib2  
import re  
import os  
import socket   
import time   
import httplib2
from BeautifulSoup import BeautifulSoup
#全局变量，如果频繁urlopen一个网站会被远程连接强制断掉，这里为了递归调用函数，接着从断掉的那一页重新开始爬取  
CONSTANT = 1  
m = {}
m2 = {}
def MailSearch(url,begin_page,end_page):
    global CONSTANT  
    #正则匹配，这里我要获取@xxxx.com.cn结尾的邮箱号  
    # p = re.compile(r'"(http://www.baidu.com/link[^"]+?)"')    
    p = re.compile(r'"(http://cache.baiducontent.com/[^"]+?)"')    
    base_dir = "./"  
    for i in range(begin_page, end_page+1):  
        print i  
        CONSTANT +=1  
        try:  
            sName = string.zfill(i,7) + '.txt' #自动填充成六位的文件名  
            f = open(base_dir + sName,'w+')
			# socket.setdefaulttimeout(10)
            sleep_download_time = 1  
            time.sleep(sleep_download_time) #这里时间自己设定    
            requests = urllib2.urlopen(url + str(i*10)) #这里是要读取内容的,不断变更的地址  
            content = requests.read()#读取，一般会在这里报异常，被爬取的网站强行断掉  
            #之前直接获取到网页内容，然后直接正则匹配发现始终只能拿到第一页的内容后面的没有了  
            #只能先下载下来，再正则再删去该网页，不知道哪位大神能给出解释  
            f.write(content)  
            f.close()  
            requests.close()#记得要关闭   
        except:
            print("-----error:",url)
            continue
          
        file_object = open(base_dir + sName)  
        try:  
            all_the_text = file_object.read()
            mailAddress= p.findall(all_the_text)

            f = open(r'url.txt','a')
            for num in mailAddress:  
                s = str(num)
                if not m.has_key(s):
                    print s
                    m[s] = 1
                    try:
                        requests = urllib2.urlopen(s)
                        content = requests.read()
                        soup = BeautifulSoup(content)
                        r = soup.findAll("base")
                        for rrr in r:
                            rr = rrr.get("href")
                            if rr:
                                if not m2.has_key(rr):
                                    m2[rr] = 1
                                    print rr
                                    requests.close()
                                    f.write(rr)
                                    f.write('\n')
                    except:
                        print "error"
                        continue
            f.close()
        finally:
            file_object.close()
        #即时删除下载的网页  
        os.remove(base_dir + sName)

bdurl = "http://www.baidu.com/s?wd=site%3Asmm.cn%20彩虹乐园&oq=%40xxxx.com.cn&tn=sitehao123&ie=utf-8&pn="
bdurls = "http://www.baidu.com/s?wd=site%3Asmm.cn%20"
bdurle = "&oq=%40xxxx.com.cn&tn=sitehao123&ie=utf-8&pn="
#设置起始页，终止页  
begin_page = 0  
end_page = 50
#-------- 在这里输入参数 ------------------  
#调用
strs = [
"泻心汤",
"彩虹乐园"]

MailSearch(bdurl,begin_page,end_page)