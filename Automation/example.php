<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
 class A {
	 public $a = 5;
	 
	 function __construct($param){
		 $this->a = $param;
	 }
	 
	 function afiseaza(){
		 echo $this->a;
	 }
 }
 
 class B extends A{
	 public $b = 15;
	 
	 function afiseaza(){
		 parent::afiseaza();
		 echo $this->b;
	 }
	 
 }
 
 $obiect = new B(10);
 $obiect->afiseaza();

