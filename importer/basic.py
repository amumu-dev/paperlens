def intHash(buf):
    ret = 0
    for i in range(len(buf)):
        ret = ret * 31 + ord(buf[i])
    return ret % 200000000
