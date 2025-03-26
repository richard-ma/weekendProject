# mpiexec --oversubscribe -n 5 python helloworld_mpi.py
# --oversubscribe is used to run more processes than the number of physical cores
from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()
print("hello world from process ", rank)