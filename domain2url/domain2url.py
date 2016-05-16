# coding:utf8
import sys
import os
import config
import MySQLdb
import shutil
import traceback
import random
import uuid
import math
import time


def conn_sql(i):
    print 'connect database ...'
    try:
        db = MySQLdb.connect(
                host=config.DATABASES['HOST'],
                port=config.DATABASES['PORT'],
                user=config.DATABASES['USER'],
                passwd=config.DATABASES['PASSWORD'],
                db=config.DATABASES['NAME_PRE'] + str(i),
                charset='utf8',
        )
    except Exception, info:
        print traceback.format_exc()
        sys.exit()

    print 'connect database success!'
    print 'database name:' + config.DATABASES['NAME_PRE'] + str(i)
    return db


def write(res_list):
    file_path = './out'
    if os.path.isdir(file_path):
        shutil.rmtree(file_path, True)
    os.mkdir(file_path)

    index = 0
    file_names = []
    print len(res_list)
    print config.MAX_URL_NUM
    file_num = int(math.ceil( len(res_list) / config.MAX_URL_NUM ))
    for i in range(0, file_num):
        uid = uuid.uuid1()
        file_name = 'sitemap_{0}.xml'.format(uid)
        file_names.append(file_name)
        f = open('./out/' + file_name, 'w')

        head_str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset\nxmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\nxmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\nxsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\nhttp://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n"
        f.write(head_str)
        for j in range(i * config.MAX_URL_NUM, (i + 1) * config.MAX_URL_NUM):
            outstr = "<url>\n<loc>http://" + res_list[j] + "</loc>\n<priority>0.8</priority>\n</url>\n"
            f.write(outstr)
        f.write("</urlset>\n")
        f.close()

    date = time.strftime('%Y-%m-%d',time.localtime(time.time()))
    file_name = './out/{0}.txt'.format(date)
    f = open(file_name, 'w')
    f.write(config.DATABASES['NAME_PRE'] + str(config.DATABASES['FROM_ID']) + '-' +
            config.DATABASES['NAME_PRE'] + str(config.DATABASES['TO_ID']) + '\n')
    f.write(str(config.MAX_URL_NUM) + '\n')
    f.write(str(len(res_list)) + '\n')
    f.write(str(file_num) + '\n')
    for name in file_names:
        f.write(name + '\n')
    f.close()

def main():
    print 'domain2url start...'

    res_url = []

    for i in range(config.DATABASES['FROM_ID'], config.DATABASES['TO_ID'] + 1):
        db = conn_sql(i)

        cursor = db.cursor()

        domain_base_sql = "select domain from shell"
        try:
            cursor.execute(domain_base_sql)
        except Exception as e:
            print traceback.format_exc()
            sys.exit()
        shell_info = cursor.fetchone()


        index = 0
        step = 1000
        while True:
            url_base_sql = "select url from link LIMIT {0},{1}".format(index, step)
            try:
                count = cursor.execute(url_base_sql)
            except Exception as e:
                print traceback.format_exc()
                break

            link_infos = cursor.fetchall()

            for info in link_infos:
                str = shell_info[0] + '/' + info[0]
                res_url.append(str)

            print "readed database name: {0}{1} , {2} - {3}".format(config.DATABASES['NAME_PRE'] ,
                                                                    i, index + 1, index + count)

            if count < step:
                print "readed database name: {0}{1} , done!".format(config.DATABASES['NAME_PRE'] , i)
                break

            index += step

        cursor.close()
        db.commit()
        db.close()
    print len(res_url)
    random.shuffle(res_url)
    write(res_url)
    print 'have done!'

if __name__ == '__main__':
    main()