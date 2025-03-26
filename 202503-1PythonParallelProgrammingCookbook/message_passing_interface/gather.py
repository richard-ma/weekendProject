from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()
size = comm.Get_size()

data = (rank + 1) ** 2
data = comm.gather(data, root=0)

if rank == 0:
    print("rank = %d, data = %s" % (rank, data))

    for i in range(1, size):
        value = data[i]
        print("rank = %d, data = %s" % (i, value))