__author__ = 'xlvector'

from Tkinter import *
import random
import math
from operator import itemgetter


def RDT(points,wx,hy, max_depth):
    splits = []
    for k in range(0,max_depth):
        axis = random.randint(0,1)
        sv = random.random()
        splits.append([axis, sv])
    root = [0, 0, -1, -1, 0, points]
    tree = [root]
    Q = [root]
    k = 0
    while len(Q) > 0:
        [depth, index, left, right, predict, ps] = Q.pop()
        if len(ps) == 0 and predict != 0:
            print index
        if depth >= max_depth or len(ps) < 10:
            continue
        [axis,sv] = splits[depth]
        pleft = dict()
        pright = dict()
        p1 = 0
        p2 = 0
        for p, c in ps.items():
            if c == 0:
                continue
            f = [float(int(p/1000))/(wx*1.0), float(int(p%1000))/(hy*1.0)]
            if f[axis] < sv:
                pleft[p] = c
                p1 += c
            else:
                pright[p] = c
                p2 += c
        tree[index][5] = dict()

        p1 = p1 / (len(pleft) * 1.0 + 10.0)
        tree[index][2] = len(tree)
        leftNode = [depth+1, len(tree), -1, -1, p1, pleft]
        tree.append(leftNode)
        Q.append(leftNode)
            

        p2 = p2 / (len(pright) * 1.0 + 10.0)
        tree[index][3] = len(tree)
        rightNode = [depth+1, len(tree), -1, -1, p2, pright]
        tree.append(rightNode)
        Q.append(rightNode)
    return [tree, splits]

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


for k in range(2500):
    x = int(random.uniform(0, W))
    y = int(random.uniform(0, H))
    d = math.sqrt((x - W / 2) * (x - W / 2) + (y - H / 2) * (y - H / 2))

    if d > 70 and d < 120:
        if x - W / 2 > 0:
            points[getHash(x,y)] = 1
        else:
            points[getHash(x,y)] = 0

    if d > 150 and d < 190:
        if x - W / 2 > 0:
            points[getHash(x,y)] = -1
        else:
            points[getHash(x,y)] = 0

random.seed(198571)
maxvalues = []
minvalues = []
for step in range(0,200):
    pb = dict()
    nn = dict()
    for tree in range(0,64):
        [tree, splits] = RDT(points,W,H,80)
        for h, c in points.items():
            if c >= 1 or c <= -1:
                continue
            i = int(h/1000)
            j = int(h%1000)
            f = [i / (W*1.0),j / (H*1.0)]
            k = 0
            pk = 0
            while True:
                [depth, index, left, right, predict, ps] = tree[k]
                if left < 0 and right < 0:
                    pk = predict
                    break
                [axis, sv] = splits[depth]
                if f[axis] < sv:
                    if left < 0:
                        pk = predict
                        break
                    else:
                        k = left
                else:
                    if right < 0:
                        pk = predict
                        break
                    else:
                        k = right
            if h not in nn:
                nn[h] = 0
                pb[h] = 0
            nn[h] += 1
            pb[h] += pk

    if len(nn) == 0:
        break
    maxval = max(pb.values())
    minval = min(pb.values())
    
    maxvalues.append(maxval)
    minvalues.append(minval)
    avemax = sum(maxvalues) / (1.0 * len(maxvalues))
    avemin = sum(minvalues) / (1.0 * len(minvalues))

    #if maxval < avemax:
    #    maxval = avemax

    #if minval > avemin:
    #    minval = avemin
    
    for key, value in pb.items():
        if value > maxval * 0.95 and value > avemax * 0.9:
            points[key] = 1
        if value < minval * 0.95 and value < avemin * 0.9:
            points[key] = -1


for p,c in points.items():
    px = int(p / 1000)
    py = p % 1000
    if c < 0:
        w.create_oval(px - 2,py-2, px+2,py+2, fill="red",outline="red")
    elif c > 0:
        w.create_oval(px - 2,py-2, px+2,py+2, fill="blue",outline="blue")
    else:
        w.create_oval(px - 3,py-3, px+3,py+3, fill="yellow", outline="yellow")
        
mainloop()