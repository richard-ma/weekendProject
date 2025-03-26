import numpy as np
from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()
size = comm.Get_size()

array_size = 10
recvdata = np.zeros(array_size, dtype=int)
senddata = (rank + 1) * np.arange(array_size, dtype=int)

print("Process %d sending %s" % (rank, senddata))
comm.Reduce(senddata, recvdata, op=MPI.SUM, root=0)
print('on task ', rank, 'after Reduce: data = ', recvdata)