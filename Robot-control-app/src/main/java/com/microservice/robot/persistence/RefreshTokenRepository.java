package com.microservice.robot.persistence;

import com.microservice.robot.data.RefreshToken;
import org.springframework.data.mongodb.repository.MongoRepository;

import java.util.List;
import java.util.Optional;

public interface RefreshTokenRepository extends MongoRepository<RefreshToken, String> {

	Optional<RefreshToken> findByToken(String refreshToken);

	List<RefreshToken> findAllByUserId(String refreshToken);
}

