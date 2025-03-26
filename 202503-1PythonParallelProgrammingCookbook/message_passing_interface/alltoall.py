from mpi4py import MPI
import numpy as np

comm = MPI.COMM_WORLD
rank = comm.Get_rank()
size = comm.Get_size()

senddata = (rank + 1) * np.arange(size, dtype=int)
recvdata = np.empty(size, dtype=int)

comm.Alltoall(senddata, recvdata)
print("process %d sending %s receiving %s" % (rank, senddata, recvdata))