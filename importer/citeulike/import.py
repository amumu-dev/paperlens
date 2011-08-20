#ArNet
import MySQLdb
import sys
sys.path.append("../")
import paperlens_import

def Extract(buf):
    p1 = buf.find('>')
    key = ''
    value = ''
    p1 = p1 + 1
    p2 = buf.find('<', p1)
    key = buf[0:p1].strip()
    value = buf[p1:p2].strip()
    return (key, value)

def GetUserNameFromLink(buf):
    p1 = buf.find('/user/')
    if p1 < 0:
        return ''
    p1 += len('/user/')
    p2 = buf.find('/', p1)
    if p2 < 0:
        return ''
    return buf[p1:p2]

connection = MySQLdb.connect (host = "127.0.0.1", user = "paperlens", passwd = "paper1ens", db = "paperlens")
cursor = connection.cursor()
connection.commit()


try:
    data = open("../../../data/citeulike.txt")
    cursor.execute("truncate table paper_citeulike_users")
    citeseer_id_map = dict()
    title = ''
    link = ''
    n = 0
    for line in data:
        (key, value) = Extract(line)
        if line.find("<item ") >= 0:
            if len(title) > 20:
                hashvalue = paperlens_import.intHash(title.lower())
                cursor.execute("select count(*),id from paper where hashvalue=%s",(hashvalue))
                row = cursor.fetchone()
                if int(row[0]) == 1:
                    paper_id = int(row[1])
                    user_name = GetUserNameFromLink(link)
                    if len(user_name) == 0:
                        continue
                    cursor.execute("replace into paper_citeulike_users (paper_id, user_name) values (%s, %s)",(paper_id, user_name))

                    if n % 10000 == 0:
                        print n, title, user_name
                    n = n + 1

            title = ''
            link = ''
        if key == "<title>":
            title = value
        if key == "<link>":
            link = value
    data.close()
    connection.commit()
    cursor.close()
    connection.close()
except MySQLdb.Error, e:
    print e.args[0], e.args[1]
