import os
import argparse
import socket
import struct
import select
import time


ICMP_ECHO_REQUEST = 8
DEFAULT_TIMEOUT = 2
DEFAULT_COUNT = 4


class Pinger:
    def __init__(self, target_host, timeout=DEFAULT_TIMEOUT, count=DEFAULT_COUNT):
        self.target_host = target_host
        self.count = count
        self.timeout = timeout
        
    def do_checksum(self, source_string):
        """
        Calculate the checksum of the given source string.
        """
        count_to = (len(source_string) // 2) * 2
        sum = 0
        for i in range(0, count_to, 2):
            sum += (source_string[i] << 8) + (source_string[i + 1])
        if len(source_string) % 2:
            sum += source_string[-1]
        sum = (sum >> 16) + (sum & 0xffff)
        sum += (sum >> 16)
        return ~sum & 0xffff

    def receive_ping(self, sock, ID, timeout):
        """
        Receive a ping response.
        """
        time_remaining = timeout
        while True:
            start_time = time.time()
            ready = select.select([sock], [], [], time_remaining)
            time_spent = time.time() - start_time
            if ready[0] == []:
                return None 
            
            time_received = time.time()
            packet, addr = sock.recvfrom(1024)
            icmp_header = packet[20:28]
            type, code, checksum, packet_id, sequence = struct.unpack('bbHHh', icmp_header)
            if packet_id == ID:
                bytes_in_double = struct.calcsize('d')
                time_sent = struct.unpack('d', packet[28:28 + bytes_in_double])[0]
                return time_received - time_sent
            
            time_remaining -= time_spent
            if time_remaining <= 0:
                return None

    def send_ping(self, sock, ID):
        """
        Send a ping request.
        """
        header = struct.pack('bbHHh', ICMP_ECHO_REQUEST, 0, 0, ID, 1)
        bytes_in_double = struct.calcsize('d')
        data = struct.pack('d', time.time())
        checksum = self.do_checksum(header + data)
        header = struct.pack('bbHHh', ICMP_ECHO_REQUEST, 0, socket.htons(checksum), ID, 1)
        packet = header + data
        sock.sendto(packet, (self.target_host, 1))
    
    def ping_once(self):
        """
        Ping the target host once.
        """
        sock = socket.socket(socket.AF_INET, socket.SOCK_RAW, socket.getprotobyname('icmp'))
        ID = os.getpid() & 0xFFFF
        self.send_ping(sock, ID)
        delay = self.receive_ping(sock, ID, self.timeout)
        sock.close()
        return delay

    def ping(self):
        """
        Ping the target host multiple times.
        """
        print(f"Pinging {self.target_host} with {self.count} packets:")
        for i in range(self.count):
            delay = self.ping_once()
            if delay is None:
                print(f"Request timed out.")
            else:
                print(f"Reply from {self.target_host}: time={delay * 1000:.2f} ms")
            time.sleep(1)


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description='Ping a host using ICMP.')
    parser.add_argument('host', type=str, help='The target host to ping.')
    parser.add_argument('-c', '--count', type=int, default=DEFAULT_COUNT, help='Number of ping requests to send.')
    parser.add_argument('-t', '--timeout', type=int, default=DEFAULT_TIMEOUT, help='Timeout for each ping request in seconds.')
    
    args = parser.parse_args()
    
    pinger = Pinger(args.host, args.timeout, args.count)
    pinger.ping()