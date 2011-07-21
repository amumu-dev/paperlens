from django.db import models
from django.contrib.auth.models import User

# Create your models here.
class Sina_Request_Token(models.Model):
    thekey = models.TextField()
    secret = models.TextField()
    
class SinaLink(models.Model):
    theuser = models.ForeignKey(User)
    sinaid = models.TextField()
    ackey = models.TextField()#access token
    acsecret = models.TextField()
