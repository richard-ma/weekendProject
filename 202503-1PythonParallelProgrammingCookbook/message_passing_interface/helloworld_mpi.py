# mpiexec -n 2 python helloworld_mpi.py
from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()
print("hello world from process ", rank)