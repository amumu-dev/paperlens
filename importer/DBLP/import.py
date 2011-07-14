import _mysql
import sys

def Extrack(buf):
    p1 = buf.find('>')
    key = ''
    value = ''
    p1 = p1 + 1
    p2 = buf.find('<', p1)
    key = buf[0:p1]
    value = buf[p1:p2]
    return [key,value]

connection = _mysql.connect('localhost', 'paperlens', 'paper1ens', 'paperlens')

##data = open("../../../data/dblp.xml")
##
##authors = []
##title = ''
##
##for line in data:
##    if line.find('<incollection') >= 0:
##        authors = []
##        title = ''
##    elif line.find('</incollection>') >= 0:
##        authors = []
##        title = ''
##    else:
##        [key,value] = Extrack(line)
##        if key == "<author>":
##            print key, value
