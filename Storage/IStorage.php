<?php 
namespace WeiChatLib;

/**
* 
*/
interface IStorage{
	public function write($content);
	public function read();
}
