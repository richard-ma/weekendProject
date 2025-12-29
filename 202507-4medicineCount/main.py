import pandas as pd
import os


if __name__ == "__main__":
    # Load the dataset
    df = pd.read_csv(os.path.join("data", "medicineList.csv"))

    # Display the first few rows of the dataframe
    print(df.head())

    # Count the number of medicines
    medicine_count = df['Medicine'].nunique()
    
    # Print the count of unique medicines
    print(f"Total unique medicines: {medicine_count}")