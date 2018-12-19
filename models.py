from django.db import models
from django.contrib.postgres.fields import ArrayField
from django.contrib.postgres.fields import JSONField

class account(models.Model):
    email=models.CharField(max_length=100)
    phone=models.CharField(max_length=100)
    name=models.CharField(max_length=100)
    company=models.TextField(blank=True,null=True)
    designation=models.TextField(blank=True,null=True)

    address=models.TextField(blank=True,null=True)
    state=models.TextField(blank=True,null=True)
    country=models.TextField(blank=True,null=True)
    pincode=models.TextField(blank=True,null=True)

    password=models.CharField(max_length=200)
    def __str__(self):return str(self.email)

class product(models.Model):
    owner=models.ForeignKey('account',on_delete=models.CASCADE)
    img=models.TextField(blank=True,null=True)
    name=models.CharField(max_length=100)
    email=models.CharField(max_length=100)
    phone=models.CharField(max_length=100)
    def __str__(self):return str(self.name)

class lead(models.Model):
    owner=models.ForeignKey('account',on_delete=models.CASCADE)
    email=models.CharField(max_length=100)
    phone=models.CharField(max_length=100)
    name=models.CharField(max_length=100)
    designation=models.TextField(blank=True,null=True)
    address=models.TextField(blank=True,null=True)
    timestamp=models.DateTimeField(auto_now_add=True)
    def __str__(self):return str(self.name)


#Accessories
class account_verify(models.Model):
    email=models.TextField(max_length=100)
    verifylink=models.TextField(max_length=100)

class clickback(models.Model):
    clickback=models.TextField(unique=True)
    owner=models.ForeignKey('account',on_delete=models.CASCADE)

class dummy(models.Model):
    owner=models.ForeignKey('account',on_delete=models.CASCADE)
    timestamp=models.DateTimeField(auto_now_add=True)
    textt=models.TextField(blank=True,null=True)
    arey=ArrayField(models.TextField(blank=True,null=True))
    data = JSONField(blank=True,null=True)
    tu=models.TextField(unique=True)
#=======================-
#v1

#TODO SWITCH TO TIMESCALEDB in next version (extension to pgsql)
#records
#class send_records(models.Model):
    #sender=models.ForeignKey('account',on_delete=models.CASCADE)
    #leads=ArrayField(models.TextField())
    #content=ArrayField(models.TextField(blank=True,null=True))
    #clickback_link=models.TextField(unique=True)
    #timestamp=models.DateTimeField(auto_now_add=True)
#class clickback_records(models.Model):
    #owner=models.ForeignKey('account',on_delete=models.CASCADE)
    #data = JSONField(blank=True,null=True)
    #clickback=models.TextField(unique=True)
