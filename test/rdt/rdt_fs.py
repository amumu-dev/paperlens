__author__ = 'xlvector'

from Tkinter import *
import random
import math
from operator import itemgetter

def Distance(x1, y1, x2, y2):
    return math.sqrt((x1 - x2) * (x1 - x2) + (y1 - y2) * (y1 - y2))

def GetKey(px, py, splits,wx,hy):
    key = 1
    features = [px/(wx*1.0), py/(hy*1.0)]
    for axis, sv in splits:
        if features[axis] < sv:
            key = key * 2
        else:
            key = key * 2 + 1
    return key

def RDT(points,wx,hy):
    splits = []
    for k in range(0,30):
        axis = random.randint(0,0)
        sv = random.random()
        splits.append([axis, sv])
    prob0 = dict()
    prob1 = dict()
    total = dict()
    for p,c in points.items():
        px = int(p/1000)
        py = int(p%1000)
        key = GetKey(px, py, splits,wx,hy)
        if key not in total:
            total[key] = 0
            prob0[key] = 0
            prob1[key] = 0
        total[key] += 1
        if c < 0:
            prob0[key] += 1
        else:
            prob1[key] += 1
    for key,value in total.items():
        prob0[key] /= (value + 100.0) * 1.0
        prob1[key] /= (value + 100.0) * 1.0
    return [splits, prob0,prob1]


master = Tk()

W = 600
H = 500

w = Canvas(master, width=W, height=H, bg="white")
w.pack(fill=BOTH)

points = dict()
train = dict()
cpoints = set()
random.seed(198571)
def getHash(x, y):
    return x * 1000 + y

#for k in range(150):
#    x1 = random.gauss(320,80)
#    y1 = random.gauss(160,100)
#    x11 = int(x1 * 0.3 + y1 * 0.7)
#    y11 = int(x1 * 0.7 + y1 * 0.3)
#    if x11 < 0 or y11 < 0 or x11 > W or y11 > H:
#        continue
#    h1 = getHash(x11, y11)
#    if h1 in points:
#        continue
#    points[h1] = -1
#    if k < 75:
#        train[h1] = -1
#
#    x2 = random.gauss(320,80)
#    y2 = random.gauss(480,100)
#    x22 = int(x2 * 0.3 + y2 * 0.7)
#    y22 = int(x2 * 0.7 + y2 * 0.3)
#    if x22 < 0 or y22 < 0 or x22 > W or y22 > H:
#        continue
#    h2 = getHash(x22, y22)
#    if h2 in points:
#        continue
#    points[h2] = 1
#    if k < 75:
#        train[h2] = 1

for k in range(300):
    x = int(random.uniform(0, W))
    y = int(random.uniform(0, H))

    if x * 2 < W - 10:
        points[getHash(x,y)] = 1
    if x * 2 > W + 10:
        points[getHash(x,y)] = -1

random.seed()
p0 = dict()
p1 = dict()
nn = dict()
for tree in range(0,64):
    print tree
    [splits, prob0, prob1] = RDT(points,W,H)
    for i in range(0,W):
        for j in range(0,H):
            if (i + j) % 5 > 0:
                continue
            key = GetKey(i, j, splits,W,H)
            h = getHash(i, j)
            if h not in nn:
                nn[h] = 0
                p0[h] = 0
                p1[h] = 0
            nn[h] += 1
            if key in prob0:
                p0[h] += prob0[key]
            if key in prob1:
                p1[h] += prob1[key]
for key, value in nn.items():
    x = int(key/1000)
    y = key % 1000
    if p0[key] > p1[key]:
        w.create_line(x,y, x+1,y+1, fill="orange")
    if p1[key] > p0[key]:
        w.create_line(x,y, x+1,y+1, fill="green")

for p,c in points.items():
    px = int(p / 1000)
    py = p % 1000
    if c < 0:
        w.create_oval(px - 2,py-2, px+2,py+2, fill="red",outline="red")
    else:
        w.create_oval(px - 2,py-2, px+2,py+2, fill="blue",outline="blue")
        
mainloop()