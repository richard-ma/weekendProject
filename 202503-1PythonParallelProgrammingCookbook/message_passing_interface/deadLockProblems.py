# mpiexec --oversubscribe -n 9 python deadLockProblems.py
from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()
print("my rank is ", rank)

if rank == 1:
    data_send = 'a'
    destination_process = 5
    source_process = 5
    data_received = comm.recv(source=source_process)
    comm.send(data_send, dest=destination_process)
    print("sending data %s to process %s" % (data_send, destination_process))
    print("data received is = %s" % data_received)
if rank == 5:
    data_send = 'b'
    destination_process = 1
    source_process = 1
    comm.send(data_send, dest=destination_process)
    data_received = comm.recv(source=source_process)
    print("sending data %s to process %s" % (data_send, destination_process))
    print("data received is = %s" % data_received)
# Deadlocks can occur when two processes are waiting for each other to send data.
# In the above code, process 1 sends data to process 5 and waits for data from process 5.
# Process 5 sends data to process 1 and waits for data from process 1.
# This will cause a deadlock.
# To avoid deadlocks, we can use non-blocking communication.