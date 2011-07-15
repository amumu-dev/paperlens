import urllib2

class Crawler:

    def __init__(self, startUrl):
        self.seedUrl = startUrl
        
    def download(self, url):
        try:
            fp = urllib2.urlopen(url)
            text = ''
            while 1:
                s = fp.read()
                if not s:
                    break
                text = text + '\n' + s
            fp.close()
            return text
        except:
            print 'download exception'
            return ''
        
