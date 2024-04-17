import random
import heapq

# แทนราคาใน distance/cost matrix
distances = [[0,237,0,0,0,0,0,0,508,0,0,0,0,0,0,0,0],
[237,0,190,0,114,39,0,0,0,0,0,0,0,0,0,0,0],
[0,190,0,92,229,0,0,0,0,0,0,0,0,0,0,0,0],
[0,0,92,0,142,0,143,148,0,0,0,0,0,0,0,0,0],
[0,114,229,142,0,82,96,0,0,0,0,0,0,0,0,0,0],
[0,0,39,0,82,0,0,0,241,0,0,0,0,0,0,0,0],
[0,0,0,143,96,0,0,119,0,163,72,0,0,0,0,0,0],
[0,0,0,148,0,0,119,0,0,0,0,0,0,0,0,0,0],
[508,0,0,0,0,241,0,0,0,85,0,0,62,0,0,0,0],
[0,0,0,0,195,0,163,0,85,0,91,58,71,0,0,0,0],
[0,0,0,0,0,0,72,0,0,91,0,108,0,0,0,0,0],
[0,0,0,0,0,0,0,0,0,58,108,0,0,57,177,0,0],
[0,0,0,0,0,0,0,0,62,71,0,110,0,100,0,0,132],
[0,0,0,0,0,0,0,0,0,0,0,57,100,0,133,0,104],
[0,0,0,0,0,0,0,0,0,0,0,177,0,133,0,0,174],
[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,43],
[0,0,0,0,0,0,0,0,0,0,0,0,132,104,174,43,0]]

# รายชื่อเมือง
cities = {
    1: 'แม่ฮองสอน',
    2: 'เชียงใหม่',
    3: 'เชียงราย',
    4: 'พะเยา',
    5: 'ลำปาง',
    6: 'ลำพูน',
    7: 'แพร่',
    8: 'น่าน',
    9: 'ตาก',
    10: 'สุโขทัย',
    11: 'อุตรดิตถ์',
    12: 'พิษณุโลก',
    13: 'กำแพงเพชร',
    14: 'พิจิตร',
    15: 'เพชรบูรณ์',
    16: 'อุทัยธานี',
    17: 'นครสวรรค์',
}

# สร้างฟังก์ชันคำนวณค่าระยะทางรวมของเส้นทาง
def calculate_total_distance(route):
    total_distance = 0
    for i in range(len(route) - 1):
        total_distance += distances[route[i]][route[i + 1]]
    return total_distance

# สร้างฟังก์ชันการประเมิน (fitness function)
def evaluate_fitness(route):
    total_distance = calculate_total_distance(route)
    return -total_distance  # เราต้องการหาค่าที่น้อยที่สุด

# สร้างฟังก์ชันสร้างรายการเมืองสุ่ม
def generate_random_route(num_cities):
    route = list(range(1, num_cities + 1))
    random.shuffle(route)
    return route

# สร้าง genetic algorithm
def genetic_algorithm(population_size, num_generations):
    num_cities = len(cities)
    population = [generate_random_route(num_cities) for _ in range(population_size)]

    for generation in range(num_generations):
        population = sorted(population, key=evaluate_fitness, reverse=True)
        elite = population[:10]  # เลือก 10 สมาชิกแรก (สมาชิกดีที่สุด)

        # สร้างลูกโดยการผสมพันธุ์
        children = []
        while len(children) < population_size - len(elite):
            parent1, parent2 = random.choices(population[:50], k=2)  # เลือกสองพ่อแม่จาก 50 สมาชิกแรก
            crossover_point = random.randint(1, num_cities - 1)
            child = parent1[:crossover_point] + parent2[crossover_point:]
            children.append(child)

        # เชื่อมรวมสมาชิกแรก (elite) กับลูกที่ถูกสร้างขึ้นใหม่
        population = elite + children

        print(f"Generation {generation + 1}: Best Distance: {-evaluate_fitness(population[0])}")

    best_route = population[0]
    best_distance = -evaluate_fitness(best_route)
    return best_route, best_distance

if __name__ == "__main__":
    population_size = 100
    num_generations = 1000

    best_route, best_distance = genetic_algorithm(population_size, num_generations)

    print("Best Route:", [cities[city] for city in best_route])
    print("Best Distance:", best_distance)
