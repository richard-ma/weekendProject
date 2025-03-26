from mpi4py import MPI
import numpy as np

UP = 0
DOWN = 1
LEFT = 2
RIGHT = 3

neightbor_processes = [0, 0, 0, 0]

if __name__ == "__main__":
    comm = MPI.COMM_WORLD
    rank = comm.Get_rank()
    size = comm.Get_size()
    
    grid_rows = int(np.floor(np.sqrt(size)))
    grid_columns = int(size / grid_rows)