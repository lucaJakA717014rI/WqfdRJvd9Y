import pyodbc
# Define the connection string
conn = pyodbc.connect(
    "SERVER=localhost\\SQLEXPRESS;"  # Adjust based on your setup
    "DATABASE=StockMarket;"
    "Trusted_Connection=yes;"
)

print("Microsoft SQL Server Connection Successful!")
conn.close()
