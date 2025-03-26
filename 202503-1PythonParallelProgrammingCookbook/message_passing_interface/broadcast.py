from mpi4py import MPI
comm = MPI.COMM_WORLD
rank = comm.Get_rank()

if rank == 0:
    variable_to_share = 100
else:
    variable_to_share = None

variable_to_share = comm.bcast(variable_to_share, root=0)
print("process = %d, variable_to_share = %d" % (rank, variable_to_share))
# The bcast function is used to broadcast data from one process to all other processes.
# The root argument specifies the process that is broadcasting the data.
# The data is broadcast to all other processes.
# In the above code, process 0 broadcasts the variable variable_to_share to all other processes.
# The variable variable_to_share is received by all other processes.
# The output of the above code will be as follows:
# process = 0, variable_to_share = 100
# process = 1, variable_to_share = 100
# process = 2, variable_to_share = 100
# process = 3, variable_to_share = 100
# process = 4, variable_to_share = 100
# process = 5, variable_to_share = 100
# process = 6, variable_to_share = 100
# process = 7, variable_to_share = 100