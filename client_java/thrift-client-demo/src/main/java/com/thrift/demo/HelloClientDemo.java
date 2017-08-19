package com.thrift.demo;

import org.apache.thrift.TException;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TCompactProtocol;
import org.apache.thrift.protocol.TJSONProtocol;
import org.apache.thrift.protocol.TMultiplexedProtocol;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;
import org.apache.thrift.transport.TTransportException;

import services.demo.hiService.HiService;

/**
 * 
 *
 * @author wen
 *
 */
public class HelloClientDemo {

	public static final String SERVER_IP = "127.0.0.1";
	public static final int SERVER_PORT = 8091;
	public static final int TIMEOUT = 30000;

	/**
	 *
	 * @param userName
	 */
	public void startClient(String userName) {
		TTransport transport = null;
		try {
			transport = new TSocket(SERVER_IP, SERVER_PORT, TIMEOUT);
			// 协议要和服务端一致
			TProtocol protocol = new TBinaryProtocol(new TFramedTransport(transport));
			//Services\\Demo\\HiService 为服务名称
			TMultiplexedProtocol tMultiplexedProtocol = new TMultiplexedProtocol(protocol, "Services\\Demo\\HiService");
			transport.open();
			HiService.Client client = new HiService.Client(tMultiplexedProtocol);
			String result = client.say(userName);
			System.out.println("Thrify client result =: " + result);
		} catch (TTransportException e) {
			e.printStackTrace();
		} catch (TException e) {
			e.printStackTrace();
		} finally {
			if (null != transport) {
				transport.close();
			}
		}
	}
	


	/**
	 * @param args
	 */
	public static void main(String[] args) {
		HelloClientDemo client = new HelloClientDemo();
		 client.startClient("test");
		

	}

}